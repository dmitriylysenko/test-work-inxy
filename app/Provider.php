<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Provider
 * @package App
 * @property string hash
 * @property string provider
 * @property string location
 * @property string brand_label
 * @property string cpu
 * @property string drive_label
 * @property string price
 * @property string created_at
 * @property string updated_at
 */
class Provider extends Model
{
    protected $fillable = ['provider', 'location', 'brand_label', 'cpu', 'drive_label', 'price', 'created_at', 'updated_at'];

    public static function add($fields): self
    {
        $provider = new self();
        $provider->fill($fields);
        $provider->hash = self::generateHash($fields);
        $provider->save();

        return $provider;
    }

    public function edit($fields): void
    {
        $this->fill($fields);
        $this->hash = self::generateHash($fields);
        $this->save();
    }

    /**
     * @param $fields
     * @return string
     */
    public static function generateHash($fields): string
    {
        return hash('md5', collect($fields)->except(['price'])->join(''));
    }
}
