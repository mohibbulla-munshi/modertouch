<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $subscriber = NewsletterSubscriber::where('email', $request->email)->first();

        if ($subscriber) {
            if (! $subscriber->is_active) {
                $subscriber->update(['is_active' => true]);
                return back()->with('success', 'You have been re-subscribed!');
            }
            return back()->with('info', 'You are already subscribed.');
        }

        NewsletterSubscriber::create([
            'email'     => $request->email,
            'name'      => $request->name ?? null,
            'is_active' => true,
        ]);

        return back()->with('success', 'Thank you for subscribing!');
    }
}
