<?php

namespace App\Component;

use App\Provider;
use http\Exception\RuntimeException;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

/**
 * Class ProviderManager
 * @package App\Component
 * @property $validator
 * @property array $providersArray
 * @property $providersCollection
 * @property $providersModelsCollection
 */
class ProviderManager
{
    protected $validator;
    private $providersArray;
    protected $providersCollection;
    protected $providersModelsCollection;

    /**
     * ProviderManager constructor.
     * @throws FileNotFoundException
     */
    public function __construct()
    {
        $providers = json_decode(Storage::disk('local')->get('providers.json'), true);
        if (!isset($providers['data'])) {
            throw new RuntimeException('Response data is empty');
        }
        $this->providersArray = $providers['data'];
        $this->validator = Validator::make($providers['data'], [
            '*.provider' => 'required|string',
            '*.location' => 'required|string',
            '*.brand_label' => 'required|string',
            '*.cpu' => 'required|regex:/^[a-z0-9\-\s]*$/i',
            '*.drive_label' => 'required|string',
            '*.price' => 'required|int',
        ]);
    }

    /**
     * @return \Illuminate\Contracts\Validation\Validator
     */
    public function getValidator(): \Illuminate\Contracts\Validation\Validator
    {
        return $this->validator;
    }

    /**
     * @return $this
     */
    private function decorateData(): self
    {
        $this->providersCollection = collect($this->providersArray)
            ->map(static function ($item, $key) {
                return [
                    'hash' => Provider::generateHash($item),
                    'provider' => $item['provider'],
                    'location' => $item['location'],
                    'brand_label' => $item['brand_label'],
                    'cpu' => $item['cpu'],
                    'drive_label' => $item['drive_label'],
                    'price' => $item['price'],
                ];
            })
            ->keyBy('hash');
        $this->providersModelsCollection = Provider::all()->keyBy('hash');
        return $this;
    }

    /**
     * @return $this
     */
    private function insertItems(): self
    {
        $insertCollect = $this->providersCollection->diffKeys($this->providersModelsCollection);
        $insertCollect->chunk(1500)
            ->each(static function ($chunkedInsertCollection) {
                DB::transaction(static function () use ($chunkedInsertCollection) {
                    DB::table('providers')->insert($chunkedInsertCollection->toArray());
                });
            });
        return $this;
    }

    /**
     * @return $this
     */
    private function deleteItems(): self
    {
        $deleteCollect = $this->providersModelsCollection->diffKeys($this->providersCollection);
        $deleteCollect->keyBy('id')->keys()->chunk(1500)
            ->each(static function ($chunkedDeleteIds) {
                DB::transaction(static function () use ($chunkedDeleteIds) {
                    Provider::destroy($chunkedDeleteIds->toArray());
                });
            });
        return $this;
    }

    /**
     * @return $this
     */
    private function updateItems(): self
    {
        $this->providersCollection->each(static function ($item) {
            Provider::where('hash', $item['hash'])
                ->update(['price' => $item['price']]);
        });
        return $this;
    }

    public function updateData(): void
    {
        $this->decorateData()
            ->insertItems()
            ->deleteItems()
            ->updateItems();
    }
}
