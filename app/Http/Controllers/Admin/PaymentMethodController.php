<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::orderBy('id')->get();
        return view('admin.payment_methods.index', compact('methods'));
    }

    public function create()
    {
        return view('admin.payment_methods.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:payment_methods',
            'type'         => 'required|string|max:50',
            'description'  => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        PaymentMethod::create($validated);

        return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Payment Method added successfully.');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        return view('admin.payment_methods.edit', compact('paymentMethod'));
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:100|unique:payment_methods,name,' . $paymentMethod->id,
            'type'         => 'required|string|max:50',
            'description'  => 'nullable|string',
            'instructions' => 'nullable|string',
            'is_active'    => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $paymentMethod->update($validated);

        return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Payment Method updated successfully.');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return redirect()->route('admin.payment-methods.index')
                         ->with('success', 'Payment Method deleted successfully.');
    }
}
