@extends('layouts.Admin.app')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Page Header -->
<div class="page-header">
    <h1 class="page-title">✏️ Edit Product</h1>
    <p class="page-subtitle">Update product information and manage its settings.</p>
</div>

<style>
    .form-compact {
        padding: 1rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .form-row-single {
        grid-column: 1 / -1;
    }

    .form-field {
        display: flex;
        flex-direction: column;
    }

    .form-field label {
        font-size: 0.85rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.4rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .form-field input,
    .form-field select,
    .form-field textarea {
        height: 2.5rem;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
    }

    .form-field textarea {
        height: auto;
        min-height: 2.5rem;
        resize: vertical;
    }

    .form-field small {
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }

    .size-inputs {
        display: none;
        margin-top: 1rem;
        padding: 1rem;
        background-color: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #dee2e6;
    }
    .size-inputs.active {
        display: block;
    }

    .size-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }

    .size-field {
        display: flex;
        flex-direction: column;
    }

    .size-field label {
        font-size: 0.8rem;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.3rem;
    }

    .size-field input {
        height: 2.2rem;
        font-size: 0.85rem;
        padding: 0.4rem 0.6rem;
    }

    .card-header-compact {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }

    .section-title {
        color: #800000;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 1rem;
        margin-bottom: 0;
    }

    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .size-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

@if(session('success'))
    <div class="alert-modern alert-success-modern mb-3">{{ session('success') }}</div>
@endif

<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card-modern">
            <div class="card-header-compact">
                <div class="section-title">
                    <i class="bi bi-pencil-square"></i> Edit Product: {{ $selected_prod->name }}
                </div>
            </div>
            <div class="form-compact">
                <form action="{{ route('admin.manageproducts.update', $selected_prod->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Row 1: Product Name & Price -->
                    <div class="form-row">
                        <div class="form-field">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $selected_prod->name) }}" class="form-control-modern" required>
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label for="price">Price (₱)</label>
                            <input type="number" name="price" id="price" value="{{ old('price', $selected_prod->price) }}" class="form-control-modern" step="0.01" required>
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 2: Description & Category -->
                    <div class="form-row">
                        <div class="form-field">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control-modern" required>{{ old('description', $selected_prod->description) }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label for="category_id">Category</label>
                            <select name="category_id" id="category_id" class="form-control-modern" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $selected_prod->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 3: Stock & Size Option -->
                    <div class="form-row">
                        <div class="form-field" id="general_stock_field" style="{{ $selected_prod->has_size == 1 ? 'display:none;' : '' }}">
                            <label for="stock">Stock (General)</label>
                            <input type="number" name="stock" id="stock" value="{{ old('stock', $selected_prod->stock) }}" class="form-control-modern" min="0" placeholder="0">
                            <small>Total stock quantity</small>
                            @error('stock')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-field">
                            <label for="has_size">Requires Size?</label>
                            <select name="has_size" id="has_size" class="form-control-modern">
                                <option value="0" {{ old('has_size', $selected_prod->has_size) == 0 ? 'selected' : '' }}>No</option>
                                <option value="1" {{ old('has_size', $selected_prod->has_size) == 1 ? 'selected' : '' }}>Yes</option>
                            </select>
                            @error('has_size')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Row 4: Image Upload -->
                    <div class="form-row form-row-single">
                        <div class="form-field">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" id="image" class="form-control-modern" accept="image/*">
                            <small>Upload new image (JPG, PNG, GIF) - Leave empty to keep current image</small>
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    <!-- Size Inputs (Hidden by default) -->
                    <div id="size_inputs" class="size-inputs" style="{{ $selected_prod->has_size == 1 ? 'display:block;' : '' }}">
                        <label class="form-label-modern fw-bold mb-2">Stock per Size:</label>
                        
                        <div class="size-grid">
                            @php
                                $sizes = ['Small', 'Medium', 'Large', 'XL', 'XXL'];
                                $existingSizes = $selected_prod->sizes->pluck('stock', 'size_name')->toArray();
                            @endphp
                            
                            @foreach($sizes as $size)
                            <div class="size-field">
                                <label>{{ $size }}</label>
                                <input type="number" 
                                       name="sizes[{{ $size }}]" 
                                       value="{{ old('sizes.'.$size, $existingSizes[$size] ?? 0) }}" 
                                       class="form-control-modern size-stock-input-edit" 
                                       min="0" 
                                       onchange="calculateTotalStockEdit()"
                                       placeholder="0">
                            </div>
                            @endforeach
                        </div>

                        <div class="alert-modern alert-info-modern" id="total_stock_display">
                            <strong>Total Stock:</strong> <span id="calculated_total_edit">{{ $selected_prod->has_size ? $selected_prod->sizes->sum('stock') : 0 }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex gap-2 mt-3">
                        <button type="submit" class="btn-primary-modern flex-fill">
                            <i class="bi bi-check-lg me-1"></i> Update Product
                        </button>
                        <a href="{{ route('admin.manageproducts.index') }}" class="btn-outline-modern flex-fill text-center">
                            <i class="bi bi-x-lg me-1"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle size inputs and general stock based on "Requires Size?" selection
    document.getElementById('has_size').addEventListener('change', function() {
        const sizeInputs = document.getElementById('size_inputs');
        const generalStockField = document.getElementById('general_stock_field');
        const stockInput = document.getElementById('stock');
        
        if (this.value == '1') {
            sizeInputs.style.display = 'block';
            generalStockField.style.display = 'none';
            stockInput.value = 0;
            calculateTotalStockEdit();
        } else {
            sizeInputs.style.display = 'none';
            generalStockField.style.display = 'block';
        }
    });
    
    // Calculate total stock from all sizes in edit form
    function calculateTotalStockEdit() {
        const sizeInputs = document.querySelectorAll('.size-stock-input-edit');
        let total = 0;
        
        sizeInputs.forEach(input => {
            const value = parseInt(input.value) || 0;
            total += value;
        });
        
        document.getElementById('calculated_total_edit').textContent = total;
        return total;
    }
</script>
@endsection