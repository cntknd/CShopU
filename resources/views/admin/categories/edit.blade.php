@extends('layouts.Admin.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">✏️ Edit Category</h1>
    <p class="page-subtitle">Update category information and manage its settings.</p>
</div>

<style>
    .card-modern {
        border: none;
        border-radius: 15px;
        background-color: #fff;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }

    .btn-primary-modern {
        background: linear-gradient(135deg, #800000 0%, #a00000 100%);
        color: white;
        border: none;
        transition: all 0.3s ease;
    }
    .btn-primary-modern:hover {
        background: linear-gradient(135deg, #a00000 0%, #800000 100%);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(128, 0, 0, 0.3);
    }

    .section-title {
        color: #800000;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
</style>

        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card card-modern p-4">
                    <div class="section-title">
                        <i class="bi bi-pencil-square"></i> Edit Category
                    </div>

                    @if(session('success'))
                        <div class="alert-modern alert-success-modern">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('admin.categories.update', $category->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="name" class="form-label-modern fw-semibold">Category Name</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   class="form-control-modern" required placeholder="Enter category name">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary-modern flex-fill">
                                <i class="bi bi-check-lg me-1"></i> Update Category
                            </button>
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary flex-fill">
                                <i class="bi bi-arrow-left me-1"></i> Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
