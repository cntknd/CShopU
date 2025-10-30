@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1 class="page-title d-flex align-items-center gap-2">
                <i class="bi bi-box-seam"></i> Products
            </h1>
            <p class="page-subtitle mb-0">Manage inventory and product listings</p>
        </div>
        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#addProductModal">
            <i class="bi bi-plus-lg"></i> Add Product
        </button>
    </div>
</div>
<style>
    .section-title{color:#800000;font-weight:600;display:flex;align-items:center;gap:10px;font-size:1.1rem;margin-bottom:1rem}
    .form-compact{padding:1rem}
    .form-row{display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1rem}
    .form-row-single{grid-column:1/-1}
    .form-field{display:flex;flex-direction:column}
    .form-field label{font-size:.85rem;font-weight:600;color:#495057;margin-bottom:.4rem;text-transform:uppercase;letter-spacing:.5px}

    .form-field input,
    .form-field select {
        height: 2.5rem;
        font-size: 0.9rem;
        padding: 0.5rem 0.75rem;
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

    .size-badge {
        display: inline-block;
        padding: 3px 6px;
        margin: 1px;
        border-radius: 4px;
        font-size: 0.75rem;
        font-weight: 500;
    }
    .size-badge.in-stock {
        background-color: #d4edda;
        color: #155724;
    }
    .size-badge.out-of-stock {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Compact Card Header */
    .card-header-compact {
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 1px solid #dee2e6;
    }

    .card-header-compact .section-title {
        margin-bottom: 0;
        font-size: 1rem;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .form-row {
            grid-template-columns: 1fr;
            gap: 0.75rem;
        }
        
        .size-grid {
            grid-template-columns: 1fr;
        }
    }

    /* Profit Display Styles */
    .bg-success-light {
        background-color: #d4edda !important;
    }
    
    .bg-danger-light {
        background-color: #f8d7da !important;
    }
    
    .text-success {
        color: #155724 !important;
    }
    
    .text-danger {
        color: #721c24 !important;
    }

    .badge-success-modern {
        background-color: #d4edda !important;
        color: #155724 !important;
    }

    .badge-danger-modern {
        background-color: #f8d7da !important;
        color: #721c24 !important;
    }
</style>

<div class="row g-4">
        <!-- Add Product Form -->
        <div class="col-md-4">
            <div class="card-modern">
                <div class="card-header-compact">
                    <div class="section-title">
                        <i class="bi bi-plus-circle"></i> Add New Product
                    </div>
                </div>
                <div class="form-compact">
                    <form action="{{ route('admin.manageproducts.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Row 1: Product Name & Price -->
                        <div class="form-row">
                            <div class="form-field">
                                <label>Product Name</label>
                                <input type="text" name="pname" class="form-control-modern" required placeholder="Enter product name">
                            </div>
                            <div class="form-field">
                                <label>Selling Price (₱)</label>
                                <input type="number" name="price" class="form-control-modern" step="0.01" required placeholder="0.00">
                            </div>
                        </div>

                        <!-- Row 2: Supplier Price -->
                        <div class="form-row">
                            <div class="form-field">
                                <label>Supplier Price (₱)</label>
                                <input type="number" name="supplier_price" id="supplier_price" class="form-control-modern" step="0.01" required placeholder="0.00" oninput="calculateProfit()">
                                <small class="text-muted">Cost price from supplier</small>
                            </div>
                            <div class="form-field">
                                <label>Expected Profit</label>
                                <div class="border rounded p-2 bg-light" id="profit_display">₱0.00 (0%)</div>
                            </div>
                            <script>
                                function calculateProfit() {
                                    const supplierPrice = parseFloat(document.getElementById('supplier_price').value) || 0;
                                    const sellingPrice = parseFloat(document.getElementsByName('price')[0].value) || 0;
                                    const profit = sellingPrice - supplierPrice;
                                    const profitPercentage = supplierPrice > 0 ? (profit / supplierPrice) * 100 : 0;
                                    
                                    const display = document.getElementById('profit_display');
                                    const profitText = `₱${profit.toFixed(2)} (${profitPercentage.toFixed(1)}%)`;
                                    
                                    display.textContent = profitText;
                                    display.className = 'border rounded p-2 ' + (profit > 0 ? 'bg-success-light text-success' : 'bg-danger-light text-danger');
                                }
                                
                                // Add the event listener to the selling price input as well
                                document.getElementsByName('price')[0].addEventListener('input', calculateProfit);
                            </script>
                        </div>

                        <!-- Row 3: Description & Category -->
                        <div class="form-row">
                            <div class="form-field">
                                <label>Description</label>
                                <input type="text" name="desc" class="form-control-modern" required placeholder="Product description">
                            </div>
                            <div class="form-field">
                                <label>Category</label>
                                <select name="category_id" class="form-control-modern" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Row 3: Stock & Size Option -->
                        <div class="form-row">
                            <div class="form-field" id="general_stock_field">
                                <label>Stock (General)</label>
                                <input type="number" name="stock" id="stock_input" class="form-control-modern" min="0" required placeholder="0">
                                <small id="stock_hint">Total stock quantity</small>
                            </div>
                            <div class="form-field">
                                <label>Requires Size?</label>
                                <select name="has_size" id="has_size_select" class="form-control-modern">
                                    <option value="0" selected>No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 4: Image Upload -->
                        <div class="form-row form-row-single">
                            <div class="form-field">
                                <label>Product Image</label>
                                <input type="file" name="image" class="form-control-modern" required accept="image/*">
                                <small>Upload product image (JPG, PNG, GIF)</small>
                            </div>
                        </div>

                        <!-- Size Inputs (Hidden by default) -->
                        <div id="size_inputs" class="size-inputs">
                            <label class="form-label-modern fw-bold mb-2">Stock per Size:</label>
                            
                            <div class="size-grid">
                                @foreach (['Small', 'Medium', 'Large', 'XL', 'XXL'] as $size)
                                <div class="size-field">
                                    <label>{{ $size }}</label>
                                    <input type="number" name="sizes[{{ $size }}]" class="form-control-modern size-stock-input" min="0" value="0" onchange="calculateTotalStock()" placeholder="0">
                                </div>
                                @endforeach
                            </div>

                            <div class="alert-modern alert-info-modern" id="total_stock_display">
                                <strong>Total Stock:</strong> <span id="calculated_total">0</span>
                            </div>
                        </div>

                        <button type="submit" class="btn-primary-modern w-100 mt-2">💾 Save Product</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Product List -->
        <div class="col-md-8">
            <div class="card-modern">
                <div class="card-header-modern">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="section-title mb-0">
                            <i class="bi bi-box-seam"></i> Product List
                        </div>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.categories.index') }}" class="btn-outline-modern btn-sm">
                                <i class="bi bi-tags"></i> Manage Categories
                            </a>
                            <select id="categoryFilter" class="form-control-modern" style="width: auto;">
                                <option value="">All Categories</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-body-modern">
                    @if(count($produktomo))
                    <div class="table-responsive">
                        <table class="table-modern table">
                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Description</th>
                                    <th>Category</th>
                                    <th>Supplier Price</th>
                                    <th>Selling Price</th>
                                    <th>Profit</th>
                                    <th>Stock</th>
                                    <th>Sizes</th>
                                    <th class="text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($produktomo as $prod)
                                <tr>
                                    <td><strong>{{ $prod->name }}</strong></td>
                                    <td>{{ $prod->description }}</td>
                                    <td>
                                        @if($prod->category_id && $prod->category)
                                            <span class="badge-modern badge-primary-modern" data-category-id="{{ $prod->category->id }}">{{ $prod->category->name }}</span>
                                        @else
                                            @php
                                                $category = \App\Models\Category::find($prod->category_id);
                                            @endphp
                                            @if($category)
                                                <span class="badge-modern badge-primary-modern" data-category-id="{{ $category->id }}">{{ $category->name }}</span>
                                            @else
                                                <span class="text-muted" data-category-id="">No Category</span>
                                            @endif
                                        @endif
                                    </td>
                                    <td><strong class="text-primary">₱{{ number_format($prod->supplier_price, 2) }}</strong></td>
                                    <td><strong>₱{{ number_format($prod->price, 2) }}</strong></td>
                                    <td>
                                        @php
                                            $profit = $prod->price - $prod->supplier_price;
                                            $profitPercentage = $prod->supplier_price > 0 ? ($profit / $prod->supplier_price) * 100 : 0;
                                        @endphp
                                        <span class="badge-modern {{ $profit > 0 ? 'badge-success-modern' : 'badge-danger-modern' }}">
                                            ₱{{ number_format($profit, 2) }} ({{ number_format($profitPercentage, 1) }}%)
                                        </span>
                                    </td>
                                    <td>
                                        @if($prod->has_size && $prod->sizes->count() > 0)
                                            <span class="badge-modern badge-info-modern">{{ $prod->sizes->sum('stock') }}</span>
                                        @else
                                            <span class="badge-modern badge-info-modern">{{ $prod->stock }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($prod->has_size && $prod->sizes->count() > 0)
                                            @foreach($prod->sizes as $size)
                                                <span class="size-badge {{ $size->stock > 0 ? 'in-stock' : 'out-of-stock' }}">
                                                    {{ $size->size_name }}: {{ $size->stock }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.manageproducts.edit', $prod->id) }}" class="btn-outline-modern btn-sm">Edit</a>
                                            <form action="{{ route('admin.manageproducts.destroy', $prod->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this product?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-danger-modern btn-sm">Delete</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                        <div class="alert-modern alert-info-modern text-center">No products available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script>
    // Toggle size inputs
    document.getElementById('has_size_select').addEventListener('change', function() {
        const sizeInputs = document.getElementById('size_inputs');
        const generalStockField = document.getElementById('general_stock_field');
        const stockInput = document.getElementById('stock_input');
        
        if (this.value == '1') {
            sizeInputs.classList.add('active');
            generalStockField.style.display = 'none';
            stockInput.required = false;
            stockInput.value = 0;
            calculateTotalStock();
        } else {
            sizeInputs.classList.remove('active');
            generalStockField.style.display = 'block';
            stockInput.required = true;
            stockInput.value = '';
        }
    });

    function calculateTotalStock() {
        const sizeInputs = document.querySelectorAll('.size-stock-input');
        let total = 0;
        sizeInputs.forEach(input => total += parseInt(input.value) || 0);
        document.getElementById('calculated_total').textContent = total;
        return total;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const select = document.getElementById('has_size_select');
        if (select.value == '1') {
            document.getElementById('size_inputs').classList.add('active');
            document.getElementById('general_stock_field').style.display = 'none';
        }
    });

    // Category filtering
    document.getElementById('categoryFilter').addEventListener('change', function() {
        const selectedCategory = this.value;
        const rows = document.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const categoryCell = row.querySelector('td:nth-child(3)');
            if (categoryCell) {
                const categoryId = categoryCell.querySelector('span[data-category-id]')?.getAttribute('data-category-id');
                if (selectedCategory === '' || categoryId === selectedCategory) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });
    });
</script>

@endsection
