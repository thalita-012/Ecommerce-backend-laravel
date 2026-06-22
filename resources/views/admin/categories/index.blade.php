@extends('admin.layouts.app')

@section('title', 'Categories')

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

    /* Modern Action Button Overrides */
    .btn-action-primary {
        background-color: #024cab;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        border: none;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        transition: background-color 0.2s ease;
    }

    .btn-action-primary:hover {
        background-color: #00367a;
        color: #ffffff;
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

    /* Premium Text Formatting Utilities */
    .slug-code {
        background-color: #f1f5f9;
        color: #334155;
        padding: 0.2rem 0.5rem;
        font-size: 0.8rem;
        border-radius: 6px;
        font-family: monospace;
    }

    /* Soft Blue Pill for Counters */
    .badge-counter-soft {
        background-color: #e6f0fa;
        color: #024cab;
        padding: 0.35rem 0.7rem;
        font-weight: 600;
        font-size: 0.75rem;
        border-radius: 8px;
        display: inline-block;
    }

    /* Clean Subtle Actions Buttons */
    .btn-table-edit {
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

    .btn-table-edit:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    .btn-table-delete {
        background-color: #fef2f2;
        color: #ef4444;
        font-size: 0.82rem;
        font-weight: 600;
        padding: 0.4rem 0.8rem;
        border-radius: 8px;
        border: none;
        cursor: pointer;
        transition: all 0.15s ease;
    }

    .btn-table-delete:hover {
        background-color: #fca5a5;
        color: #991b1b;
    }

    /* Ultra-Premium SweetAlert2 Clean Overrides */
    .premium-popup-modal {
        border-radius: 20px !important;
        padding: 2.5rem 2rem !important;
        background: #ffffff !important;
        border: 1px solid rgba(15, 23, 42, 0.08) !important;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08) !important;
        max-width: 400px !important;
    }

    .premium-popup-title {
        font-family: system-ui, -apple-system, sans-serif !important;
        font-size: 1.35rem !important;
        font-weight: 700 !important;
        letter-spacing: -0.02em !important;
        color: #0f172a !important;
        padding: 0 !important;
        margin-bottom: 0.5rem !important;
    }

    .premium-popup-html {
        font-family: system-ui, -apple-system, sans-serif !important;
        font-size: 0.92rem !important;
        line-height: 1.5 !important;
        color: #64748b !important;
        padding: 0 !important;
        margin-bottom: 2rem !important;
    }

    .premium-actions-container {
        gap: 12px !important;
        width: 100% !important;
        margin: 0 !important;
    }

    .premium-confirm-btn {
        background-color: #ef4444 !important;
        color: #ffffff !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        padding: 0.7rem 1.5rem !important;
        border-radius: 12px !important;
        border: none !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.15) !important;
        transition: all 0.2s ease !important;
        flex: 1;
        text-align: center;
    }

    .premium-confirm-btn:hover {
        background-color: #dc2626 !important;
        box-shadow: 0 6px 16px rgba(220, 38, 38, 0.2) !important;
        transform: translateY(-1px);
    }

    .premium-cancel-btn {
        background-color: #f1f5f9 !important;
        color: #475569 !important;
        font-weight: 600 !important;
        font-size: 0.88rem !important;
        padding: 0.7rem 1.5rem !important;
        border-radius: 12px !important;
        border: 1px solid #e2e8f0 !important;
        transition: all 0.15s ease !important;
        flex: 1;
        text-align: center;
    }

    .premium-cancel-btn:hover {
        background-color: #e2e8f0 !important;
        color: #0f172a !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center page-header-wrapper">
    <div>
        <h2 class="page-title">Categories</h2>
        <div class="page-subtitle">Organize and manage your public store product collections.</div>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn-action-primary">
        Add New Category
    </a>
</div>

<div class="card content-card border-0">
    <div class="table-responsive">
        <table class="table table-modern mb-0">
            <thead>
                <tr>
                    <th style="width: 10%;">ID</th>
                    <th style="width: 25%;">Name</th>
                    <th style="width: 25%;">Slug URL</th>
                    <th style="width: 15%;">Products</th>
                    <th style="width: 13%;">Created</th>
                    <th style="width: 12%; text-align: right;">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                <tr>
                    <td class="fw-bold text-dark">#{{ $category->id }}</td>
                    <td class="fw-bold text-dark">{{ $category->name }}</td>
                    <td><span class="slug-code">{{ $category->slug }}</span></td>
                    <td>
                        <span class="badge-counter-soft">
                            {{ $category->products_count }} item{{ $category->products_count === 1 ? '' : 's' }}
                        </span>
                    </td>
                    <td>{{ $category->created_at->format('M d, Y') }}</td>
                    <td style="text-align: right;">
                        <div class="d-inline-flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-table-edit">Edit</a>
                            
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="delete-category-form d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn-table-delete trigger-delete-alert">Delete</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center text-muted py-5 font-size-0.95rem">
                        No catalog categories discovered inside the records system.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $categories->links() }}
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.trigger-delete-alert').forEach(button => {
            button.addEventListener('click', function(e) {
                const form = this.closest('.delete-category-form');

                Swal.fire({
                    title: 'Remove Category?',
                    text: 'This action is permanent and will drop this asset records entry immediately.',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, Delete',
                    cancelButtonText: 'Cancel',
                    reverseButtons: true,
                    showClass: {
                        popup: 'animate__animated animate__fadeIn animate__faster'
                    },
                    hideClass: {
                        popup: 'animate__animated animate__fadeOut animate__faster'
                    },
                    customClass: {
                        popup: 'premium-popup-modal',
                        title: 'premium-popup-title',
                        htmlContainer: 'premium-popup-html',
                        actions: 'premium-actions-container',
                        confirmButton: 'premium-confirm-btn',
                        cancelButton: 'premium-cancel-btn'
                    },
                    buttonsStyling: false,
                    backdrop: `
                        rgba(15, 23, 42, 0.15)
                        backdrop-filter: blur(4px);
                    `
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection