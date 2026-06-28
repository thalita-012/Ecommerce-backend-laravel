@extends('admin.layouts.app')

@section('title', 'Products')

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

        /* Premium Product Thumbnail Formatting */
        .product-thumbnail {
            width: 44px;
            height: 44px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            background-color: #f8fafc;
        }

        .thumbnail-placeholder {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            font-size: 0.7rem;
            font-weight: 500;
            color: #94a3b8;
            background-color: #f1f5f9;
            border-radius: 8px;
            text-transform: uppercase;
        }

        /* Modern Soft Badges */
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

        .badge-danger-soft {
            background-color: #fef2f2;
            color: #ef4444;
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

        .premium-popup-modal {
            border-radius: 20px !important;
            padding: 2.5rem 2rem !important;
            background: #ffffff !important;
            border: 1px solid rgba(15, 23, 42, 0.08) !important;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08) !important;
            max-width: 400px !important;
        }

        /* Elegant, humanistic header styling */
        .premium-popup-title {
            font-family: system-ui, -apple-system, sans-serif !important;
            font-size: 1.35rem !important;
            font-weight: 700 !important;
            letter-spacing: -0.02em !important;
            color: #0f172a !important;
            padding: 0 !important;
            margin-bottom: 0.5rem !important;
        }

        /* Balanced structural prose text description */
        .premium-popup-html {
            font-family: system-ui, -apple-system, sans-serif !important;
            font-size: 0.92rem !important;
            line-height: 1.5 !important;
            color: #64748b !important;
            padding: 0 !important;
            margin-bottom: 2rem !important;
        }

        /* Fine-tuned container grid spacing for action buttons */
        .premium-actions-container {
            gap: 12px !important;
            width: 100% !important;
            margin: 0 !important;
        }

        /* Modern high-contrast action interactive items */
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

        .premium-confirm-btn:active {
            transform: translateY(0);
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
            <h2 class="page-title">Products</h2>
            <div class="page-subtitle">Manage stock pricing, categories, and inventory parameters.</div>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn-action-primary">
            Add New Product
        </a>
    </div>

    <div class="card content-card border-0">
        <div class="table-responsive">
            <table class="table table-modern mb-0">
                <thead>
                    <tr>
                        <th style="width: 8%;">Image</th>
                        <th style="width: 27%;">Product Name</th>
                        <th style="width: 18%;">Category</th>
                        <th style="width: 13%;">Price</th>
                        <th style="width: 12%;">Stock</th>
                        <th style="width: 12%;">Created</th>
                        <th style="width: 10%; text-align: right;">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                        class="product-thumbnail">
                                @else
                                    <div class="thumbnail-placeholder">Empty</div>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">{{ $product->name }}</td>
                            <td>{{ $product->category->name }}</td>
                            <td class="fw-bold text-dark">${{ number_format($product->price, 2) }}</td>
                            <td>
                                @if ($product->stock <= 0)
                                    <span class="badge-pill badge-danger-soft">Out of Stock</span>
                                @elseif ($product->stock <= 5)
                                    <span class="badge-pill badge-warning-soft">Low Stock ({{ $product->stock }} left)</span>
                                @else
                                    <span class="badge-pill badge-success-soft">{{ $product->stock }} units</span>
                                @endif
                            </td>
                            <td>{{ $product->created_at->format('M d, Y') }}</td>
                            <td style="text-align: right;">
                                <div class="d-inline-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn-table-edit">Edit</a>

                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                        class="delete-product-form d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn-table-delete trigger-delete-alert">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-5 font-size-0.95rem">
                                No retail items discovered inside the catalog records.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.trigger-delete-alert').forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('.delete-product-form');

                    Swal.fire({
                        title: 'Remove Product?',
                        text: 'This action is permanent and will drop this asset records entry immediately.',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, Delete',
                        cancelButtonText: 'Cancel',
                        reverseButtons: true,

                        // Native CSS styling transitions over harsh animations
                        showClass: {
                            popup: 'animate__animated animate__fadeIn animate__faster'
                        },
                        hideClass: {
                            popup: 'animate__animated animate__fadeOut animate__faster'
                        },

                        // Modular aesthetic overrides
                        customClass: {
                            popup: 'premium-popup-modal',
                            title: 'premium-popup-title',
                            htmlContainer: 'premium-popup-html',
                            actions: 'premium-actions-container',
                            confirmButton: 'premium-confirm-btn',
                            cancelButton: 'premium-cancel-btn'
                        },
                        buttonsStyling: false,

                        // Premium background glass-morphism mask config
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
