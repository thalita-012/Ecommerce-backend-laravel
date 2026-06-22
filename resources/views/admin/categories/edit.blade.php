@extends('admin.layouts.app')

@section('title', 'Edit Category')

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

    /* Minimal UI Form Card Container */
    .content-card {
        border: 1px solid #f1f5f9;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px -2px rgba(15, 23, 42, 0.02);
    }

    /* Sleek Clean Form Inputs */
    .form-label {
        font-size: 0.82rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #64748b;
        margin-bottom: 0.5rem;
    }

    .form-control {
        border: 1px solid #cbd5e1;
        border-radius: 10px;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        color: #1e293b;
        background-color: #ffffff;
        transition: all 0.15s ease;
    }

    .form-control:focus {
        border-color: #024cab;
        box-shadow: 0 0 0 3px rgba(2, 76, 171, 0.1);
        background-color: #ffffff;
    }

    /* Modern Action Buttons */
    .btn-action-primary {
        background-color: #024cab;
        color: #ffffff;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        border: none;
        transition: background-color 0.2s ease;
    }

    .btn-action-primary:hover {
        background-color: #00367a;
        color: #ffffff;
    }

    .btn-action-secondary {
        background-color: #f1f5f9;
        color: #475569;
        font-weight: 600;
        font-size: 0.88rem;
        padding: 0.6rem 1.25rem;
        border-radius: 12px;
        border: none;
        text-decoration: none;
        text-align: center;
        transition: background-color 0.15s ease;
    }

    .btn-action-secondary:hover {
        background-color: #e2e8f0;
        color: #0f172a;
    }

    /* Validation Feedback Enhancements */
    .form-control.is-invalid {
        border-color: #ef4444;
        background-image: none;
    }
    
    .form-control.is-invalid:focus {
        box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1);
    }

    .invalid-feedback {
        font-size: 0.82rem;
        font-weight: 500;
        color: #ef4444;
        margin-top: 0.35rem;
    }
</style>

<div class="page-header-wrapper">
    <h2 class="page-title">Edit Category</h2>
    <div class="page-subtitle">Modify details and optimization options for this collection group.</div>
</div>

<div class="row">
    <div class="col-md-7 col-lg-6">
        <div class="card content-card border-0">
            <div class="card-body p-4">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="form-label">Category Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" 
                               id="name" name="name" value="{{ old('name', $category->name) }}" autocomplete="off" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2 pt-2">
                        <button type="submit" class="btn btn-action-primary">Update Category</button>
                        <a href="{{ route('admin.categories.index') }}" class="btn btn-action-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection