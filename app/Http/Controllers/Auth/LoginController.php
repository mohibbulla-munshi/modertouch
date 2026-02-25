<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if ($user && $user->is_banned) {
            throw ValidationException::withMessages([
                'email' => 'Your account has been suspended. Reason: ' . ($user->ban_reason ?? 'Policy violation'),
            ]);
        }

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->isManager()) {
                return redirect()->intended(route('admin.dashboard'));
            }
            return redirect()->intended(route('home'));
        }

        throw ValidationException::withMessages([
            'email' => 'These credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('home')->with('success', 'You have been logged out.');
    }
}
