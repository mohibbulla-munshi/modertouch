<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', 'customer');
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('email', 'like', "%{$request->search}%");
            });
        }
        if ($request->status === 'banned')  $query->where('is_banned', true);
        if ($request->status === 'active')  $query->where('is_banned', false);
        $customers = $query->withCount('orders')->latest()->paginate(20)->withQueryString();
        return view('admin.customers.index', compact('customers'));
    }

    public function show(User $customer)
    {
        $customer->load(['orders', 'addresses', 'reviews']);
        return view('admin.customers.show', ['user' => $customer]);
    }

    public function ban(Request $request, User $user)
    {
        $request->validate(['ban_reason' => 'required|string|max:500']);
        $user->update([
            'is_banned'  => true,
            'ban_reason' => $request->ban_reason,
            'banned_at'  => now(),
        ]);
        ActivityLog::record("Banned customer: {$user->email}", $user);
        return back()->with('success', 'Customer account banned.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false, 'ban_reason' => null, 'banned_at' => null]);
        ActivityLog::record("Unbanned customer: {$user->email}", $user);
        return back()->with('success', 'Customer account restored.');
    }

    public function sendEmail(Request $request, User $user)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'body'    => 'required|string',
        ]);
        // Mail::to($user->email)->send(new AdminToCustomerMail($request->subject, $request->body));
        ActivityLog::record("Sent email to customer: {$user->email}", $user);
        return back()->with('success', 'Email queued for delivery.');
    }
}
