@extends('admin.layouts.app')

@section('title', 'Order Details')

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

    /* Modern Back Action Button */
    .btn-action-back {
        background-color: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 0.5rem 1rem;
        border-radius: 10px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.15s ease;
    }

    .btn-action-back:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    /* Minimal UI Content Cards */
    .content-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.02);
        overflow: hidden;
        height: 100%;
    }

    .content-card .card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #f1f5f9;
        padding: 1.25rem 1.5rem;
    }

    .content-card .card-header h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        margin: 0;
    }

    /* Information Grid Row Lists */
    .info-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .info-list li {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        font-size: 0.9rem;
        border-bottom: 1px dashed #f1f5f9;
    }

    .info-list li:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }

    .info-list li:first-child {
        padding-top: 0;
    }

    .info-label {
        font-weight: 500;
        color: #64748b;
    }

    .info-value {
        font-weight: 600;
        color: #1e293b;
        text-end;
    }

    /* Table Design Overhauls */
    .table-modern th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #fafafa;
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern td {
        padding: 1.1rem 1.5rem;
        vertical-align: middle;
        font-size: 0.9rem;
        color: #475569;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern tfoot th, 
    .table-modern tfoot td {
        padding: 1.25rem 1.5rem;
        font-size: 1rem;
        border-top: 2px solid #f1f5f9;
        border-bottom: none;
    }

    /* Soft App Pill Badges */
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

    .address-box {
        font-size: 0.92rem;
        line-height: 1.6;
        color: #475569;
        background-color: #fafafa;
        padding: 1rem;
        border-radius: 10px;
        border: 1px dashed #cbd5e1;
    }
</style>

<div class="mb-3">
    <a href="{{ route('admin.orders.index') }}" class="btn-action-back">← Back to Orders</a>
</div>

<div class="page-header-wrapper">
    <h2 class="page-title">Order #{{ $order->id }}</h2>
    <div class="page-subtitle">Detailed manifest of customer products and checkout shipping verification.</div>
</div>

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-3 mb-4 p-3" style="background-color: #e6f7ed; color: #1ea956;">
        <strong>✓ Success!</strong> {{ session('success') }}
    </div>
@endif

<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card content-card border-0">
            <div class="card-header">
                <h5>Order Information</h5>
            </div>
            <div class="card-body p-4">
                <ul class="info-list">
                    <li>
                        <span class="info-label">Order Reference ID</span>
                        <span class="info-value text-dark">#{{ $order->id }}</span>
                    </li>
                    <li>
                        <span class="info-label">Customer Name</span>
                        <span class="info-value text-dark">{{ $order->user->name }}</span>
                    </li>
                    <li>
                        <span class="info-label">Email Address</span>
                        <span class="info-value text-muted fw-normal">{{ $order->user->email }}</span>
                    </li>
                    <li>
                        <span class="info-label">Placement Date</span>
                        <span class="info-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                    </li>
                    <li>
                        <span class="info-label">Fullfilled Status</span>
                        <span class="info-value">
                            <span class="badge-pill {{ $order->status === 'completed' ? 'badge-success-soft' : ($order->status === 'pending' ? 'badge-warning-soft' : 'badge-danger-soft') }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </span>
                    </li>
                    <li>
                        <span class="info-label">Aggregate Gross Total</span>
                        <span class="info-value text-dark fw-bold">${{ number_format($order->total_price, 2) }}</span>
                    </li>
                    <li class="pt-3 border-top mt-2 flex-column align-items-stretch border-0">
                        <span class="info-label mb-2 d-block">Update Order Status</span>
                        <form action="{{ route('admin.orders.update', $order) }}" method="POST" class="d-flex gap-2">
                            @csrf
                            @method('PATCH')
                            <select name="status" class="form-select form-select-sm fw-semibold" style="border-radius: 8px; border-color: #cbd5e1;">
                                <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Processing</option>
                                <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            <button type="submit" class="btn btn-sm text-white fw-bold px-3" style="background-color: #024cab; border-radius: 8px;">Save</button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card content-card border-0">
            <div class="card-header">
                <h5>Shipping Address</h5>
            </div>
            <div class="card-body p-4">
                @if($order->shipping_address)
                    @php
                        $address = $order->shipping_address;
                        $addressData = null;
                        if (is_string($address)) {
                            $decoded = json_decode($address, true);
                            if (json_last_error() === JSON_ERROR_NONE) {
                                $addressData = $decoded;
                            }
                        } elseif (is_array($address)) {
                            $addressData = $address;
                        }
                    @endphp

                    @if($addressData)
                        <div class="address-box border-0 bg-light p-3 rounded-3" style="line-height: 1.8;">
                            <div class="mb-1"><strong>Name:</strong> {{ $addressData['full_name'] ?? 'N/A' }}</div>
                            <div class="mb-1"><strong>Email:</strong> {{ $addressData['email'] ?? 'N/A' }}</div>
                            <div class="mb-1"><strong>Phone:</strong> {{ $addressData['phone'] ?? 'N/A' }}</div>
                            <div class="mb-1"><strong>Address:</strong> {{ $addressData['address_line1'] ?? $addressData['street'] ?? 'N/A' }}</div>
                            @if(!empty($addressData['address_line2']))
                                <div class="mb-1"><strong>Address Line 2:</strong> {{ $addressData['address_line2'] }}</div>
                            @endif
                            <div class="mb-1"><strong>City/State/Zip:</strong> {{ $addressData['city'] ?? 'N/A' }}, {{ $addressData['state'] ?? 'N/A' }} {{ $addressData['postal_code'] ?? $addressData['zip'] ?? '' }}</div>
                            <div class="mb-0"><strong>Country:</strong> {{ $addressData['country'] ?? 'N/A' }}</div>
                        </div>
                    @else
                        <div class="address-box">
                            {!! nl2br(e($order->shipping_address)) !!}
                        </div>
                    @endif
                @else
                    <div class="text-muted py-4 font-size-0.9rem">
                        No physical destination delivery parameters provided for this checkout sequence.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="card content-card border-0">
    <div class="card-header">
        <h5>Order Items</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th style="width: 45%;">Purchased Item</th>
                    <th style="width: 15%; text-align: right;">Unit Price</th>
                    <th style="width: 15%; text-align: center;">Quantity</th>
                    <th style="width: 25%; text-align: right;">Line Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        <div class="fw-bold text-dark">{{ $item->product->name }}</div>
                        <div class="text-muted small mt-0.5">SKU: {{ $item->product->slug }}</div>
                    </td>
                    <td style="text-align: right;" class="fw-medium text-dark">${{ number_format($item->price, 2) }}</td>
                    <td style="text-align: center;" class="fw-bold text-secondary">{{ $item->quantity }}</td>
                    <td style="text-align: right;" class="fw-bold text-dark">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="3" style="text-align: right;" class="text-muted fw-semibold">Grand Total Price:</th>
                    <td style="text-align: right;" class="fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
@endsection