<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{ShippingZone, ShippingRate};
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::with('rates')->get();
        return view('admin.shipping.index', compact('zones'));
    }

    public function create() { return view('admin.shipping.create'); }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|max:100',
            'regions'    => 'nullable|string',
            'is_active'  => 'boolean',
            'rates'      => 'nullable|array',
            'rates.*.name'      => 'required|string|max:100',
            'rates.*.rate'      => 'required|numeric|min:0',
            'rates.*.free_over' => 'nullable|numeric|min:0',
        ]);

        $zone = ShippingZone::create([
            'name'      => $data['name'],
            'regions'   => $data['regions'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);

        foreach ($data['rates'] ?? [] as $rateData) {
            $zone->rates()->create($rateData);
        }

        return redirect()->route('admin.shipping.index')->with('success', 'Shipping zone created.');
    }

    public function edit(ShippingZone $shippingZone)
    {
        $shippingZone->load('rates');
        return view('admin.shipping.edit', compact('shippingZone'));
    }

    public function update(Request $request, ShippingZone $shippingZone)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:100',
            'regions'   => 'nullable|string',
            'is_active' => 'boolean',
        ]);
        $shippingZone->update([...$data, 'is_active' => $request->boolean('is_active')]);
        return redirect()->route('admin.shipping.index')->with('success', 'Zone updated.');
    }

    public function destroy(ShippingZone $shippingZone)
    {
        $shippingZone->delete();
        return redirect()->route('admin.shipping.index')->with('success', 'Zone deleted.');
    }
}
