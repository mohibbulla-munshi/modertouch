<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Inquiry, ActivityLog};
use Illuminate\Http\Request;

class InquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = Inquiry::latest();
        if ($request->status === 'unread') $query->unread();
        if ($request->status === 'read')   $query->where('is_read', true);
        $inquiries = $query->paginate(20)->withQueryString();
        return view('admin.inquiries.index', compact('inquiries'));
    }

    public function show(Inquiry $inquiry)
    {
        $inquiry->update(['is_read' => true]);
        return view('admin.inquiries.show', compact('inquiry'));
    }

    public function reply(Request $request, Inquiry $inquiry)
    {
        $request->validate(['reply' => 'required|string']);
        $inquiry->update(['reply' => $request->reply, 'replied_at' => now(), 'is_read' => true]);
        // Mail::to($inquiry->email)->send(new InquiryReplyMail($inquiry));
        return back()->with('success', 'Reply sent.');
    }

    public function destroy(Inquiry $inquiry)
    {
        $inquiry->delete();
        return redirect()->route('admin.inquiries.index')->with('success', 'Inquiry deleted.');
    }
}
