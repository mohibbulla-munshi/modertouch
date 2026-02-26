<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{User, ActivityLog};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CustomerController extends Controller
{
    public function index()
    {
        return view('admin.customers.index');
    }

    /**
     * Server-side DataTables AJAX endpoint.
     */
    public function datatable(Request $request)
    {
        $draw        = (int) $request->input('draw', 1);
        $start       = (int) $request->input('start', 0);
        $length      = (int) $request->input('length', 25);
        $search      = $request->input('search.value', '');
        $orderColIdx = (int) $request->input('order.0.column', 0);
        $orderDir    = $request->input('order.0.dir', 'desc') === 'asc' ? 'asc' : 'desc';

        // Column mapping for ordering
        $cols    = ['created_at', 'id', 'name', 'email', 'id', 'created_at', 'id'];
        $orderBy = $cols[$orderColIdx] ?? 'created_at';

        $query = User::where('role', 'customer')->withCount('orders');

        // Status filter
        if ($request->filled('status')) {
            if ($request->status === 'banned') $query->where('is_banned', true);
            if ($request->status === 'active') $query->where('is_banned', false);
        }

        $totalRecords = (clone $query)->count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        $filteredRecords = (clone $query)->count();
        $customers = $query->orderBy($orderBy, $orderDir)->offset($start)->limit($length)->get();

        $rows = $customers->map(function ($c) {
            // Avatar + Name column
            $name = '
            <div class="d-flex align-items-center gap-2">
                <div style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.8rem;flex-shrink:0">
                    '.strtoupper(substr($c->name, 0, 1)).'
                </div>
                <div>
                   <div style="font-weight:600;font-size:.875rem">'.$c->name.'</div>
                   '.($c->phone ? '<div style="font-size:.7rem;color:var(--text-3)">'.$c->phone.'</div>' : '').'
                </div>
            </div>';

            // Email column
            $email = '<div style="font-size:.84rem;color:var(--text-2)">'.$c->email.'</div>';

            // Status Badge
            $status = $c->is_banned
                ? '<span style="background:rgba(239,68,68,.1);color:#DC2626;padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Banned</span>'
                : '<span style="background:rgba(13,115,119,.1);color:var(--teal);padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700">Active</span>';

            // Action
            $showUrl = route('admin.customers.show', $c->id);
            $actions = '<a href="'.$showUrl.'" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>';

            return [
                'created_at_raw' => $c->created_at->toISOString(),
                'id'         => '<span style="color:var(--text-3);font-size:.8rem">'.$c->id.'</span>',
                'name'       => $name,
                'email'      => $email,
                'orders'     => '<span style="font-weight:700;color:var(--primary)">'.$c->orders_count.'</span>',
                'joined'     => '<div style="font-size:.8rem;color:var(--text-2)">'.$c->created_at->format('d M Y').'</div>',
                'status'     => $status,
                'actions'    => $actions,
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data'            => $rows,
        ]);
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
