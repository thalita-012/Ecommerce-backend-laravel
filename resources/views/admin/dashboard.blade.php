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
            padding: 1.1rem 1.25rem;
            height: 100%;
            transition: transform 0.25s ease, box-shadow 0.25s ease;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px -4px rgba(15, 23, 42, 0.06);
        }

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

        .stat-label {
            font-size: 0.85rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.75rem;
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: -1px;
            line-height: 1;
            color: #0f172a;
            margin-bottom: 0.75rem;
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
        <h2 class="page-title">Dashboard Overview</h2>
        <div class="page-subtitle">Track live customer checkout transactions and inventory levels.</div>
    </div>

    @if(isset($lowStockProducts) && $lowStockProducts->count() > 0)
    <div class="alert alert-warning border-0 shadow-sm rounded-3 mb-4 p-3" style="background: #fff8e6; border-left: 5px solid #f59e0b !important;">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <div class="p-2 rounded-circle bg-warning text-white d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    ⚠️
                </div>
                <div>
                    <h6 class="mb-1 fw-bold text-dark">Inventory Restock Alert</h6>
                    <p class="mb-0 text-muted small">The following <strong>{{ $lowStockProducts->count() }}</strong> product(s) are low in stock. Please restock to ensure continuous customer checkout.</p>
                </div>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                @foreach($lowStockProducts as $lowProd)
                    <a href="{{ route('admin.products.edit', $lowProd) }}" class="btn btn-sm btn-outline-warning text-dark fw-semibold bg-white border-warning-subtle">
                        {{ $lowProd->name }} ({{ $lowProd->stock }} left) → Restock
                    </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card stat-card-featured">
                <div class="card-body p-0">
                    <div class="stat-label">Total Products</div>
                    <h2 class="stat-number">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body p-0">
                    <div class="stat-label">Total Categories</div>
                    <h2 class="stat-number">{{ $totalCategories }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body p-0">
                    <div class="stat-label">Total Orders</div>
                    <h2 class="stat-number">{{ $totalOrders }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card stat-card">
                <div class="card-body p-0">
                    <div class="stat-label">Total Users</div>
                    <h2 class="stat-number">{{ $totalUsers }}</h2>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-6">
            <div class="card content-card border-0">
                <div class="card-header">
                    <h5>Recent Customer Orders</h5>
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
                            @forelse ($recentOrders as $index => $order)
                                <tr>
                                    <td class="fw-bold text-dark">#{{ $order->id }}</td>
                                    <td class="fw-medium">{{ $order->user->name }}</td>
                                    <td class="fw-bold text-dark">${{ number_format($order->total_price, 2) }}</td>
                                    <td>
                                        <span
                                            class="badge-pill {{ $order->status === 'completed' ? 'badge-success-soft' : 'badge-warning-soft' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-4">No recent orders yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card content-card border-0">
                <div class="card-header">
                    <h5>Inventory Overview</h5>
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
                            @foreach ($topProducts as $product)
                                <tr>
                                    <td class="fw-bold text-dark">{{ $product->name }}</td>
                                    <td class="fw-medium">${{ number_format($product->price, 2) }}</td>
                                    <td>
                                        @if ($product->stock > 10)
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
