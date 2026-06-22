@extends('admin.layouts.app')

@section('title', 'Dashboard Overview')

@section('content')
<style>
    /* Premium Modern App Dashboard Structure */
    .page-header-wrapper {
        margin-bottom: 1.5rem;
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
    
    /* Smaller, More Compact Stat Cards */
    .stat-card {
        border: 1px solid #f1f5f9;
        border-radius: 14px;
        background: #ffffff;
        box-shadow: 0 2px 12px -2px rgba(15, 23, 42, 0.02);
        padding: 1.1rem 1.25rem; /* Reduced padding */
        height: 100%;
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.06);
    }

    /* Highlight Featured Card Styling (Matches 'Total Projects' block in blue) */
    .stat-card-featured {
        background: linear-gradient(135deg, #024cab 0%, #002d66 100%);
        border: none;
    }

    .stat-card-featured .stat-label {
        color: rgba(255, 255, 255, 0.8);
    }

    .stat-card-featured .stat-number {
        color: #ffffff;
    }

    .stat-card-featured .stat-subtext {
        color: rgba(255, 255, 255, 0.9);
        background-color: rgba(255, 255, 255, 0.15);
    }

    .stat-label {
        font-size: 0.85rem; /* Smaller font label */
        font-weight: 600;
        color: #475569;
        margin-bottom: 0.75rem; /* Tighter margin */
    }

    .stat-number {
        font-size: 2rem; /* Reduced from 2.75rem for a cleaner size */
        font-weight: 700;
        letter-spacing: -1px;
        line-height: 1;
        color: #0f172a;
        margin-bottom: 0.75rem;
    }

    .stat-subtext {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.5rem;
        font-size: 0.7rem; /* Smaller micro-text */
        font-weight: 500;
        border-radius: 6px;
        background-color: #f1f5f9;
        color: #64748b;
    }

    /* Content Area Layout Containers */
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
        padding: 1.25rem;
    }

    .content-card .card-header h5 {
        font-weight: 700;
        font-size: 1.05rem;
        color: #0f172a;
        margin: 0;
    }

    /* Minimal UI Table Configurations */
    .table-modern th {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        background-color: #fafafa;
        padding: 0.85rem 1.25rem;
        border-bottom: 1px solid #f1f5f9;
    }

    .table-modern td {
        padding: 0.9rem 1.25rem;
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
        font-size: 0.7rem;
        border-radius: 8px;
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
</style>

<div class="page-header-wrapper">
    <h2 class="page-title">Dashboard</h2>
    <div class="page-subtitle">Plan, prioritize, and accomplish your tasks with ease.</div>
</div>

<div class="row g-3 mb-4"> <div class="col-sm-6 col-xl-3">
        <div class="card stat-card stat-card-featured">
            <div class="card-body p-0">
                <div class="stat-label">Total Products</div>
                <h2 class="stat-number">{{ \App\Models\Product::count() }}</h2>
                <div class="stat-subtext">Increased from last month</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="stat-label">Total Categories</div>
                <h2 class="stat-number">{{ \App\Models\Category::count() }}</h2>
                <div class="stat-subtext">Active store lines</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="stat-label">Total Orders</div>
                <h2 class="stat-number">{{ \App\Models\Order::count() }}</h2>
                <div class="stat-subtext">Increased from last month</div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card">
            <div class="card-body p-0">
                <div class="stat-label">Total Users</div>
                <h2 class="stat-number">{{ \App\Models\User::where('is_admin', false)->count() }}</h2>
                <div class="stat-subtext">Registered clients</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card content-card border-0">
            <div class="card-header">
                <h5>Recent Orders</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Order::latest()->limit(5)->get() as $order)
                        <tr>
                            <td class="fw-bold text-dark">#{{ $order->id }}</td>
                            <td class="fw-medium">{{ $order->user->name }}</td>
                            <td class="fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                            <td>
                                <span class="badge-pill {{ $order->status === 'completed' ? 'badge-success-soft' : 'badge-warning-soft' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="card content-card border-0">
            <div class="card-header">
                <h5>Top Products</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-modern mb-0">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Stock Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Product::latest()->limit(5)->get() as $product)
                        <tr>
                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                            <td class="fw-medium">${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if($product->stock > 10)
                                    <span class="badge-pill badge-success-soft">{{ $product->stock }} available</span>
                                @elseif($product->stock > 0)
                                    <span class="badge-pill badge-warning-soft">{{ $product->stock }} low stock</span>
                                @else
                                    <span class="badge-pill badge-danger-soft">Out of stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection