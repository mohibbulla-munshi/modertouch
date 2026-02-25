<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;

class NewsletterController extends Controller
{
    public function index(Request $request)
    {
        $query = NewsletterSubscriber::latest();
        if ($request->search) $query->where('email', 'like', "%{$request->search}%");
        $subscribers = $query->paginate(30)->withQueryString();
        return view('admin.newsletters.index', compact('subscribers'));
    }

    public function destroy(NewsletterSubscriber $newsletter)
    {
        $newsletter->delete();
        return back()->with('success', 'Subscriber removed.');
    }

    public function export()
    {
        $headers = ['Content-Type' => 'text/csv', 'Content-Disposition' => 'attachment; filename="subscribers.csv"'];
        $callback = function () {
            $f = fopen('php://output', 'w');
            fputcsv($f, ['Email', 'Name', 'Subscribed At']);
            NewsletterSubscriber::where('is_active', true)->chunk(500, function ($rows) use ($f) {
                foreach ($rows as $r) fputcsv($f, [$r->email, $r->name, $r->created_at]);
            });
            fclose($f);
        };
        return response()->stream($callback, 200, $headers);
    }
}
