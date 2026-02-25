<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->latest();
        if ($request->user_id) $query->where('user_id', $request->user_id);
        if ($request->action)  $query->where('action', 'like', "%{$request->action}%");
        $logs = $query->paginate(30)->withQueryString();
        return view('admin.activity.index', compact('logs'));
    }
}
