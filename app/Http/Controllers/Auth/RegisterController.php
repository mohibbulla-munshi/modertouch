<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        try {
            // Log the incoming request for debugging (strip password)
            \Log::info('Registration attempt:', $request->except(['password', 'password_confirmation']));

            $messages = [
                'phone.regex' => 'Please enter a valid Bangladeshi mobile number (e.g. 01700112233)',
            ];

            $data = $request->validate([
                'name'                  => 'required|string|max:100',
                'email'                 => 'required|email|unique:users,email',
                'phone'                 => ['nullable', 'string', 'regex:/^(?:\+88|88)?01[3-9]\d{8}$/'],
                'password'              => 'required|string|min:8|confirmed',
            ], $messages);

            // Normalize Phone Number (Strip +88 or 88 to perfectly save 01XXXXXXXXX)
            $normalizedPhone = null;
            if (!empty($data['phone'])) {
                $normalizedPhone = preg_replace('/^(?:\+88|88)/', '', $data['phone']);
            }

            $user = User::create([
                'name'     => $data['name'],
                'email'    => $data['email'],
                'phone'    => $normalizedPhone,
                'password' => Hash::make($data['password']),
                'role'     => 'customer',
            ]);

            \Log::info('User created successfully: ' . $user->id);

            // Auto-claim any previous guest orders placed with this email
            try {
                \App\Models\Order::whereNull('user_id')
                    ->where('guest_email', $user->email)
                    ->update([
                        'user_id'     => $user->id,
                        'guest_name'  => null,
                        'guest_email' => null,
                        'guest_phone' => null,
                    ]);
            } catch (\Exception $e) {
                \Log::error('Failed to claim guest orders: ' . $e->getMessage());
                // Non-critical, continue
            }

            event(new Registered($user));

            return redirect()->route('login')
                ->with('success', 'Registration successful! Please login to your new account.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Registration Validation Failed:', $e->errors());
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Registration Crash: ' . $e->getMessage());
            \Log::error($e->getTraceAsString());
            return redirect()->back()->withInput()->with('error', 'Something went wrong. Please try again.');
        }
    }
}
