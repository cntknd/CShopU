@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title">🏷️ Manage Categories</h1>
    <p class="page-subtitle">Organize your products with categories for better management and customer experience.</p>
</div>
<style>
    .category-badge{background:linear-gradient(135deg,#e3f2fd 0%,#bbdefb 100%);color:#1976d2;padding:.5rem 1rem;border-radius:20px;font-weight:500;border:1px solid #90caf9}
    .section-title{color:#800000;font-weight:600;display:flex;align-items:center;gap:10px;font-size:1.1rem;margin-bottom:1rem}
</style>
<div class="row g-4">
    <div class="col-md-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-header-modern">
                <div class="section-title"><i class="bi bi-plus-circle"></i> Add New Category</div>
            </div>
            <div class="card-body-modern">
                <form action="{{ route('admin.categories.store') }}" method="post">@csrf
                    <div class="form-group-modern">
                        <label class="form-label-modern">Category Name</label>
                        <input type="text" name="name" class="form-control-modern" required placeholder="Enter category name">
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    <button type="submit" class="btn-primary-modern w-100">
                        <i class="bi bi-plus-lg me-1"></i> Add Category
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Categories List -->
    <div class="col-md-8">
        <div class="card-modern animate-fade-in-up">
            <div class="card-header-modern">
                <div class="section-title">
                    <i class="bi bi-tags"></i> Category List
                </div>
            </div>
            <div class="card-body-modern">
                @if(count($categories))
                <div class="table-responsive">
                    <table class="table-modern table">
                        <thead>
                            <tr>
                                <th>Category Name</th>
                                <th>Products Count</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>
                                    <span class="category-badge">{{ $category->name }}</span>
                                </td>
                                <td>
                                    <span class="badge-modern badge-info-modern">{{ $category->products_count }} products</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.categories.edit', $category->id) }}" 
                                           class="btn-outline-modern btn-sm">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form action="{{ route('admin.categories.destroy', $category->id) }}" 
                                              method="POST" class="d-inline" 
                                              onsubmit="return confirm('Delete this category? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn-danger-modern btn-sm">
                                                <i class="bi bi-trash"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                    <div class="alert-modern alert-info-modern text-center">
                        <i class="bi bi-info-circle me-2"></i>No categories available.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<!-- Flash Messages -->
@if(session('success'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-success text-white">
                <strong class="me-auto">Success</strong>
            </div>
            <div class="toast-body">
                {{ session('success') }}
            </div>
        </div>
    </div>
@endif

@if(session('error'))
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div class="toast show" role="alert">
            <div class="toast-header bg-danger text-white">
                <strong class="me-auto">Error</strong>
            </div>
            <div class="toast-body">
                {{ session('error') }}
            </div>
        </div>
    </div>
@endif

<script>
    // Auto-hide toasts after 3 seconds
    setTimeout(function() {
        const toasts = document.querySelectorAll('.toast');
        toasts.forEach(toast => {
            toast.classList.remove('show');
        });
    }, 3000);
</script>

@endsection
