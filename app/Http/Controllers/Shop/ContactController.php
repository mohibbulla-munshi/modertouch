<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Inquiry;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function showForm()
    {
        return view('shop.contact');
    }

    public function send(Request $request)
    {
        $data = $request->validate([
            'name'    => 'required|string|max:100',
            'email'   => 'required|email',
            'phone'   => 'nullable|string|max:20',
            'subject' => 'nullable|string|max:200',
            'message' => 'required|string|max:2000',
        ]);

        Inquiry::create($data);

        return back()->with('success', 'Your message has been sent! We will get back to you soon.');
    }
}
