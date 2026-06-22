@extends('admin.layouts.app')

@section('title', 'User Management')

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

    /* Soft Blue Pill for Order Counters */
    .badge-counter-soft {
        background-color: #e6f0fa;
        color: #024cab;
        padding: 0.35rem 0.7rem;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }

    .email-text {
        color: #64748b;
        font-size: 0.88rem;
    }
</style>

<div class="page-header-wrapper">
    <h2 class="page-title">Users</h2>
    <div class="page-subtitle">View and monitor registered customer accounts and account purchase logs.</div>
</div>

<div class="card content-card border-0">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th style="width: 12%;">ID</th>
                    <th style="width: 28%;">Full Name</th>
                    <th style="width: 30%;">Email Address</th>
                    <th style="width: 15%;">Total Orders</th>
                    <th style="width: 15%;">Joined Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td class="fw-bold text-dark">#{{ $user->id }}</td>
                    <td class="fw-bold text-dark">{{ $user->name }}</td>
                    <td class="email-text">{{ $user->email }}</td>
                    <td>
                        <span class="badge-counter-soft">
                            {{ $user->orders->count() }} order{{ $user->orders->count() === 1 ? '' : 's' }}
                        </span>
                    </td>
                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center text-muted py-5 font-size-0.95rem">
                        No registered customer accounts discovered inside the records system.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $users->links() }}
</div>
@endsection