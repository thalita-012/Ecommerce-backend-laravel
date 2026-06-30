<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') - E-Commerce</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        :root {
            --primary: #38bdf8;
            --primary-hover: #0ea5e9;
            --sidebar-bg: #22385b;
            --sidebar-color: #94a3b8;
            --sidebar-hover: #334155;
            --sidebar-active: #0284c7;
            --sidebar-active-text: #ffffff;
            --body-bg: #ffffff;
            --card-bg: #1e293b;
            --border-color: #334155;
        }

        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            background-color: var(--body-bg);
            color: #f8fafc;
            overflow-x: hidden;
        }

        /* Top Modern Dark Navbar */
        .navbar {
            background-color: var(--sidebar-bg);
            border-bottom: 1px solid var(--border-color);
            padding: 0.85rem 1.5rem;
        }

        .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            letter-spacing: -0.5px;
            color: #ffffff;
        }

        .navbar-brand span {
            color: var(--primary);
        }

        /* Modern Dark Sidebar Workspace */
        .sidebar {
            background-color: var(--sidebar-bg);
            min-height: calc(100vh - 61px);
            padding: 1.5rem 1rem;
            border-right: 1px solid var(--border-color);
            position: sticky;
            top: 61px;
            height: calc(100vh - 61px);
            overflow-y: auto;
        }

        .list-group-item {
            background: transparent;
            color: var(--sidebar-color);
            border: none;
            border-radius: 12px;
            margin-bottom: 0.25rem;
            padding: 0.75rem 1rem;
            font-weight: 500;
            font-size: 0.92rem;
            transition: all 0.2s ease;
        }

        .list-group-item:hover {
            background-color: var(--sidebar-hover);
            color: #ffffff;
        }

        .list-group-item.active {
            background-color: var(--sidebar-active);
            color: var(--sidebar-active-text);
            font-weight: 600;
        }

        /* Main Workspace Paddings */
        .main-content {
            padding: 2rem;
            background-color: var(--body-bg);
        }

        /* Clean System Alerts (Dark Theme Optimized) */
        .alert {
            border: none;
            border-radius: 12px;
            padding: 1rem 1.25rem;
            font-size: 0.92rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        .alert-success {
            background-color: #064e3b;
            color: #34d399;
            border-left: 4px solid #10b981;
        }

        .alert-danger {
            background-color: #7f1d1d;
            color: #fca5a5;
            border-left: 4px solid #ef4444;
        }

        .alert .btn-close {
            filter: invert(1);
        }

        /* Minimal Header Buttons */
        .btn-logout {
            color: #94a3b8;
            border: 1px solid #475569;
            border-radius: 10px;
            padding: 0.45rem 1rem;
            font-weight: 500;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .btn-logout:hover {
            background-color: #334155;
            color: #ffffff;
            border-color: #64748b;
        }

        .user-info small {
            font-size: 0.75rem;
            color: #64748b;
        }

        .user-info span {
            font-size: 0.9rem;
            color: #e2e8f0;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('admin.dashboard') }}">
                <span>E-Commerce</span> Admin
            </a>

            <div class="ms-auto d-flex align-items-center gap-3">
                <div class="user-info text-end d-none d-sm-block">
                    <small class="d-block lh-1">Logged in as</small>
                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                </div>
                <form action="{{ route('admin.logout') }}" method="POST" style="display: inline;">
                    @csrf
                    <button type="submit" class="btn btn-logout btn-sm">Logout</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 col-lg-2 sidebar">
                <div class="list-group">
                    <a href="{{ route('admin.dashboard') }}"
                        class="list-group-item list-group-item-action @if (Route::currentRouteName() === 'admin.dashboard') active @endif">
                        Dashboard Overview
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="list-group-item list-group-item-action @if (str_contains(Route::currentRouteName(), 'categories')) active @endif">
                        Product Categories
                    </a>
                    <a href="{{ route('admin.products.index') }}"
                        class="list-group-item list-group-item-action @if (str_contains(Route::currentRouteName(), 'products')) active @endif">
                        Inventory Products
                    </a>
                    <a href="{{ route('admin.orders.index') }}"
                        class="list-group-item list-group-item-action @if (str_contains(Route::currentRouteName(), 'orders')) active @endif">
                        Customer Orders
                    </a>
                    <a href="{{ route('admin.users.index') }}"
                        class="list-group-item list-group-item-action @if (str_contains(Route::currentRouteName(), 'users')) active @endif">
                        User Management
                    </a>
                </div>
            </div>

            <div class="col-md-9 col-lg-10 main-content">
                @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                        <strong>Success:</strong> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($message = Session::get('error'))
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Error:</strong> {{ $message }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                        <strong>Validation Errors:</strong>
                        <ul class="mb-0 mt-2 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div>
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>