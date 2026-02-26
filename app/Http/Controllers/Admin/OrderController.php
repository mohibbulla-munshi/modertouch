<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\{Order, OrderStatusHistory, ActivityLog};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        return view('admin.orders.index');
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

        // Map DT column index → DB column
        $cols    = ['id', 'order_number', 'shipping_name', 'id', 'total', 'payment_method', 'payment_status', 'status', 'created_at', 'id'];
        $orderBy = $cols[$orderColIdx] ?? 'id';

        $query = Order::with(['user', 'items']);

        // External filters
        if ($request->filled('status'))  $query->where('status', $request->status);
        if ($request->filled('payment')) $query->where('payment_status', $request->payment);
        if ($request->filled('method'))  $query->where('payment_method', $request->method);
        if ($request->filled('from'))    $query->whereDate('created_at', '>=', $request->from);
        if ($request->filled('to'))      $query->whereDate('created_at', '<=', $request->to);

        $total = (clone $query)->count();

        // DT global search
        if ($search) {
            $query->where(fn ($q) =>
                $q->where('order_number', 'like', "%$search%")
                  ->orWhere('shipping_name', 'like', "%$search%")
                  ->orWhere('shipping_phone', 'like', "%$search%")
                  ->orWhere('guest_email', 'like', "%$search%")
            );
        }

        $filtered = (clone $query)->count();
        $orders   = $query->orderBy($orderBy, $orderDir)->offset($start)->limit($length)->get();

        // Status badge colours
        $statusColors  = ['pending'=>'warning','confirmed'=>'info','processing'=>'primary','shipped'=>'secondary','delivered'=>'success','cancelled'=>'danger'];
        $payColors     = ['pending'=>'#D97706','paid'=>'#059669','failed'=>'#DC2626','refunded'=>'#6B7280'];
        $methodLabels  = ['cod'=>'Cash on Delivery','bank_transfer'=>'Bank Transfer','online'=>'Online'];

        $rows = $orders->map(function ($o) use ($statusColors, $payColors, $methodLabels) {
            // Order number
            $num = '<span style="font-weight:700;font-size:.82rem;color:var(--primary);font-family:\'Inter\',sans-serif">'.$o->order_number.'</span>';

            // Customer
            $email = $o->user ? $o->user->email : ($o->guest_email ?? 'Guest');
            $customer = '<div style="font-weight:600;font-size:.875rem">'.$o->shipping_name.'</div>'
                       .'<div style="font-size:.72rem;color:var(--text-3)">'.$email.'</div>'
                       .'<div style="font-size:.7rem;color:var(--text-3)">'.$o->shipping_phone.'</div>';

            // Items
            $items = '<span style="font-size:.84rem;color:var(--text-2)">'.$o->items->count().' item'.($o->items->count()!==1?'s':'').'</span>';

            // Total (cents)
            $total = '<span style="font-weight:700;font-family:\'Inter\',sans-serif;color:var(--primary)">৳'.number_format($o->total/100,2).'</span>';
            if ($o->discount > 0) $total .= '<div style="font-size:.7rem;color:#059669">-৳'.number_format($o->discount/100,2).' off</div>';

            // Payment method
            $method = '<span style="font-size:.8rem;color:var(--text-2)">'.($methodLabels[$o->payment_method] ?? $o->payment_method).'</span>';

            // Payment status
            $pc = $payColors[$o->payment_status] ?? '#6B7280';
            $payBadge = '<span style="background:rgba(0,0,0,.05);color:'.$pc.';padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;white-space:nowrap">'.ucfirst($o->payment_status).'</span>';

            // Order status
            $sc = $statusColors[$o->status] ?? 'secondary';
            $statusBadge = '<span class="badge bg-'.$sc.' bg-opacity-10 text-'.$sc.' border border-'.$sc.' border-opacity-25 py-1 px-2 text-capitalize" style="font-size:.72rem">'.ucfirst($o->status).'</span>';

            // Date
            $date = '<span style="font-size:.8rem;color:var(--text-2);white-space:nowrap">'.$o->created_at->format('d M Y').'</span>'
                   .'<div style="font-size:.7rem;color:var(--text-3)">'.$o->created_at->format('H:i').'</div>';

            // Actions
            $showUrl = route('admin.orders.show', $o->id);
            $invUrl  = route('admin.orders.invoice', $o->id);
            $delId   = 'del-ord-'.$o->id;
            $delUrl  = route('admin.orders.destroy', $o->id);

            $actions = '
            <div class="d-flex gap-1">
                <a href="'.$showUrl.'" class="btn btn-sm btn-outline-primary" title="View"><i class="bi bi-eye"></i></a>
                <a href="'.$invUrl.'" target="_blank" class="btn btn-sm" style="border:1.5px solid var(--border);color:var(--text-2)" title="Invoice"><i class="bi bi-file-earmark-pdf"></i></a>
                <form id="'.$delId.'" action="'.$delUrl.'" method="POST" style="display:inline">
                    '.csrf_field().'<input type="hidden" name="_method" value="DELETE">
                    <button type="button" class="btn btn-sm btn-danger" title="Delete" onclick="confirmDelete(\''.$delId.'\')"><i class="bi bi-trash"></i></button>
                </form>
            </div>';

            return [
                'id'       => $o->id,
                'number'   => $num,
                'customer' => $customer,
                'items'    => $items,
                'total'    => $total,
                'method'   => $method,
                'payment'  => $payBadge,
                'status'   => $statusBadge,
                'date'     => $date,
                'actions'  => $actions,
            ];
        });

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $filtered,
            'data'            => $rows,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['items.product', 'items.variant', 'user', 'statusHistory.changedBy']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status'         => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded',
            'comment'        => 'nullable|string|max:500',
        ]);

        $oldStatus = $order->status;
        $oldPaymentStatus = $order->payment_status;
        
        $order->update([
            'status'         => $request->status,
            'payment_status' => $request->payment_status
        ]);

        if ($oldStatus !== $request->status || $request->comment) {
            OrderStatusHistory::create([
                'order_id'   => $order->id,
                'status'     => $request->status,
                'comment'    => $request->comment,
                'changed_by' => auth()->id(),
            ]);
        }

        $logMsg = "Order #{$order->order_number} updated.";
        if ($oldStatus !== $request->status) $logMsg .= " Status: {$oldStatus} → {$request->status}.";
        if ($oldPaymentStatus !== $request->payment_status) $logMsg .= " Payment: {$oldPaymentStatus} → {$request->payment_status}.";
        
        ActivityLog::record($logMsg, $order);

        return back()->with('success', "Order and payment status updated successfully.");
    }

    public function invoice(Order $order)
    {
        $order->load(['items', 'user']);
        $pdf = Pdf::loadView('admin.orders.invoice', compact('order'));
        return $pdf->stream("invoice-{$order->order_number}.pdf");
    }

    public function create()
    {
        return view('admin.orders.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.orders.index')->with('info', 'Manual order created.');
    }

    public function destroy(Order $order)
    {
        ActivityLog::record("Deleted order: {$order->order_number}", $order);
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Order deleted.');
    }
}
