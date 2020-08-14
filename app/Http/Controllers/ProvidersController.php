<?php

namespace App\Http\Controllers;

use App\Component\ProviderManager;
use App\Provider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Class ProvidersController
 * @package App\Http\Controllers
 */
class ProvidersController extends Controller
{
    public function index()
    {
        $providers = DB::table('providers')->paginate(50);
        return view('providers.index', compact('providers'));
    }

    public function create()
    {
        return view('providers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'provider' => 'required|string',
            'location' => 'required|string',
            'brand_label' => 'required|string',
            'cpu' => 'required|regex:/^[a-z0-9\-\s]*$/i',
            'drive_label' => 'required|string',
            'price' => 'required|int',
        ]);
        Provider::add($request->all());

        return redirect()->route('providers.index');
    }

    public function edit($id)
    {
        $provider = Provider::find($id);
        return view('providers.edit', [
            'provider' => $provider,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'provider' => 'required|string',
            'location' => 'required|string',
            'brand_label' => 'required|string',
            'cpu' => 'required|regex:/^[a-z0-9\-\s]*$/i',
            'drive_label' => 'required|string',
            'price' => 'required|int',
        ]);

        $provider = Provider::find($id);
        $provider->edit($request->all());
        return redirect()->route('providers.index');
    }

    public function destroy($id)
    {
        Provider::find($id)->delete();

        return redirect()->route('providers.index');
    }

    public function load()
    {
        $providerManager = new ProviderManager();

        if ($providerManager->getValidator()->fails()) {
            return redirect()->route('providers.index')->withErrors($providerManager->getValidator())->withInput();
        }

        $providerManager->updateData();

        return redirect()->route('providers.index');
    }
}
