@extends('admin.layouts.app')

@section('title', 'Customer Orders')

@section('content')
<style>
    /* Premium Page Typography Headers */
    .page-header-wrapper {
        margin-bottom: 2rem;
    }
    
    .page-title {
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: -0.5px;
        color: #0f172a;
        margin-bottom: 0.15rem;
    }

    .page-subtitle {
        font-size: 0.85rem;
        color: #94a3b8;
        font-weight: 400;
    }

    /* Minimal UI Card Container */
    .content-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.02);
        overflow: hidden;
    }

    /* Table Design Overhauls */
    .table-modern th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #fafafa;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern td {
        padding: 1rem 1.25rem;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tr:last-child td {
        border-bottom: none;
    }

    /* App Pill State Variants */
    .badge-pill {
        padding: 0.35rem 0.7rem;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }
    
    .badge-success-soft {
        background-color: #e6f7ed;
        color: #1ea956;
    }

    .badge-warning-soft {
        background-color: #fffbeb;
        color: #b45309;
    }
    
    .badge-danger-soft {
        background-color: #fef2f2;
        color: #ef4444;
    }

    /* Clean Subtle Actions Buttons */
    .btn-table-view {
        background-color: #f1f5f9;
        color: #334155;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        transition: all 0.15s ease;
    }

    .btn-table-view:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }
</style>

<div class="page-header-wrapper">
    <h2 class="page-title">Orders</h2>
    <div class="page-subtitle">Track, monitor, and update live checkout customer transactions.</div>
</div>

<div class="card content-card border-0">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th style="width: 12%;">Order #</th>
                    <th style="width: 23%;">Customer</th>
                    <th style="width: 15%;">Items Count</th>
                    <th style="width: 15%;">Total Price</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 12%;">Order Date</th>
                    <th style="width: 8%; text-align: right;">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="fw-bold text-dark">#{{ ($orders->currentPage() - 1) * $orders->perPage() + $loop->iteration }}</td>
                    <td class="fw-medium">{{ $order->user->name }}</td>
                    <td>{{ $order->items_count }} item{{ $order->items_count === 1 ? '' : 's' }}</td>
                    <td class="fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                    <td>
                        <span class="badge-pill {{ $order->status === 'completed' ? 'badge-success-soft' : ($order->status === 'pending' ? 'badge-warning-soft' : 'badge-danger-soft') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn-table-view">View</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-5 font-size-0.95rem">
                        No transactions or orders discovered inside the database history.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
