@extends('layouts.admin')
@section('title', 'Activity Logs')
@section('breadcrumb')
<li class="breadcrumb-item active">Activity Log</li>
@endsection
@section('content')
<div class="page-header">
    <div>
        <h4><i class="bi bi-clock-history me-2" style="color:var(--teal)"></i>Activity Log</h4>
        <div class="page-header-sub">Audit trail of all admin actions</div>
    </div>
</div>
<div class="admin-card">
    <div class="card-header"><span><i class="bi bi-table me-2" style="color:var(--teal)"></i>Recent Activity</span></div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td style="font-size:.8rem;white-space:nowrap">
                        <div style="color:var(--text)">{{ $log->created_at->format('d M Y') }}</div>
                        <div style="color:var(--text-3)">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td>
                        @if($log->user)
                            <div class="d-flex align-items-center gap-2">
                                <div style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,var(--teal),var(--primary));display:flex;align-items:center;justify-content:center;color:#fff;font-weight:700;font-size:.72rem;flex-shrink:0">
                                    {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                </div>
                                <span style="font-size:.84rem;font-weight:600">{{ $log->user->name }}</span>
                            </div>
                        @else
                            <span style="color:var(--text-3);font-size:.84rem">System</span>
                        @endif
                    </td>
                    <td>
                        @php
                            $actionColor = match(strtolower($log->action ?? '')) {
                                'created' => ['bg'=>'rgba(16,185,129,.1)','c'=>'#059669'],
                                'updated' => ['bg'=>'rgba(240,165,0,.1)','c'=>'#B45309'],
                                'deleted' => ['bg'=>'rgba(239,68,68,.1)','c'=>'#DC2626'],
                                default   => ['bg'=>'rgba(107,114,128,.1)','c'=>'#6B7280'],
                            };
                        @endphp
                        <span style="background:{{ $actionColor['bg'] }};color:{{ $actionColor['c'] }};padding:3px 10px;border-radius:20px;font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.4px">
                            {{ $log->action }}
                        </span>
                    </td>
                    <td style="font-size:.84rem;color:var(--text-2)">
                        <span style="font-weight:600">{{ class_basename($log->model_type) }}</span>
                        <span style="color:var(--text-3)"> #{{ $log->model_id }}</span>
                    </td>
                    <td style="font-family:monospace;font-size:.8rem;color:var(--text-3)">{{ $log->ip_address }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-5" style="color:var(--text-3)">
                        <i class="bi bi-clock-history fs-2 d-block mb-2" style="opacity:.4"></i>No activity logged yet.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div style="padding:16px 20px;border-top:1px solid var(--border)">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>
    @endif
</div>
@endsection
