<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;

class VerificationController extends Controller
{
    public function notice()
    {
        if (auth()->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }
        return view('auth.verify-email');
    }

    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('home')->with('success', 'Email verified successfully!');
    }

    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('home');
        }
        $request->user()->sendEmailVerificationNotification();
        return back()->with('success', 'Verification link sent to your email!');
    }
}
