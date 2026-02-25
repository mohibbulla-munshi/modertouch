<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show()
    {
        return view('account.profile', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'  => 'required|string|max:100',
            'phone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($request->hasFile('avatar')) {
            $request->validate(['avatar' => 'image|max:1024']);
            $path = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        $user->update($data);
        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => ['required', 'confirmed', Password::min(8)],
        ]);

        if (! Hash::check($request->current_password, auth()->user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        auth()->user()->update(['password' => Hash::make($request->password)]);
        return back()->with('success', 'Password updated successfully.');
    }

    public function addresses()
    {
        $addresses = auth()->user()->addresses()->get();
        return view('account.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $data = $request->validate([
            'label'         => 'required|string|max:50',
            'name'          => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'nullable|string|max:100',
            'postal_code'   => 'nullable|string|max:20',
            'is_default'    => 'boolean',
        ]);

        if (!empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }

        auth()->user()->addresses()->create($data);
        return back()->with('success', 'Address added.');
    }

    public function updateAddress(Request $request, Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $data = $request->validate([
            'label'         => 'required|string|max:50',
            'name'          => 'required|string|max:100',
            'phone'         => 'required|string|max:20',
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'nullable|string|max:100',
            'postal_code'   => 'nullable|string|max:20',
            'is_default'    => 'boolean',
        ]);
        if (!empty($data['is_default'])) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
        $address->update($data);
        return back()->with('success', 'Address updated.');
    }

    public function deleteAddress(Address $address)
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();
        return back()->with('success', 'Address removed.');
    }
}
