<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Coupon, ActivityLog};
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::latest()->paginate(20);
        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('admin.coupons.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'code'             => 'required|string|unique:coupons|max:50',
            'description'      => 'nullable|string|max:255',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'minimum_order'    => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date|after:today',
            'is_active'        => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $coupon = Coupon::create($data);
        ActivityLog::record('Created coupon: ' . $coupon->code, $coupon);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon created.');
    }

    public function edit(Coupon $coupon)
    {
        return view('admin.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $data = $request->validate([
            'code'             => "required|string|unique:coupons,code,{$coupon->id}|max:50",
            'description'      => 'nullable|string|max:255',
            'type'             => 'required|in:percent,fixed',
            'value'            => 'required|numeric|min:0',
            'minimum_order'    => 'nullable|numeric|min:0',
            'maximum_discount' => 'nullable|numeric|min:0',
            'usage_limit'      => 'nullable|integer|min:1',
            'expires_at'       => 'nullable|date',
            'is_active'        => 'boolean',
        ]);
        $data['is_active'] = $request->boolean('is_active', true);
        $coupon->update($data);
        ActivityLog::record('Updated coupon: ' . $coupon->code, $coupon);
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon updated.');
    }

    public function destroy(Coupon $coupon)
    {
        ActivityLog::record('Deleted coupon: ' . $coupon->code, $coupon);
        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Coupon deleted.');
    }

    public function toggle(Coupon $coupon)
    {
        $coupon->update(['is_active' => ! $coupon->is_active]);
        return back()->with('success', 'Coupon status updated.');
    }
}
