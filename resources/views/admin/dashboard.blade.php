@can('admin-access')
@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title d-flex align-items-center gap-2">
        <i class="bi bi-speedometer2"></i>Dashboard
    </h1>
    <p class="page-subtitle">Welcome, {{ Auth::user()->first_name }}!</p>


<!-- Management Cards -->
<div class="row g-4">
    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">📦</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Manage Products</h3>
                <p class="text-muted mb-4">Add, edit, and organize your product inventory. Manage stock levels and product details.</p>
                <a href="{{ route('admin.manageproducts.index') }}" class="btn-primary-modern w-100">Go to Products</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">🏷️</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Categories</h3>
                <p class="text-muted mb-4">Organize products with categories. Create, edit, and manage product classifications.</p>
                <a href="{{ route('admin.categories.index') }}" class="btn-primary-modern w-100">Manage Categories</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">📋</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Orders</h3>
                <p class="text-muted mb-4">View and process customer orders. Track order status and manage fulfillment.</p>
                <a href="{{ route('admin.orders.index') }}" class="btn-primary-modern w-100">Manage Orders</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">💰</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Sales Overview</h3>
                <p class="text-muted mb-4">Track sales performance and revenue analytics. Monitor business metrics.</p>
                <a href="{{ route('admin.sales.overview') }}" class="btn-primary-modern w-100">View Sales</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">💬</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Feedbacks</h3>
                <p class="text-muted mb-4">View customer feedback and reviews. Monitor customer satisfaction.</p>
                <a href="{{ route('admin.feedbacks.index') }}" class="btn-primary-modern w-100">View Feedbacks</a>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-lg-4">
        <div class="card-modern animate-fade-in-up">
            <div class="card-body-modern text-center">
                <div class="stat-icon mb-3">👥</div>
                <h3 class="h5 fw-bold text-primary-modern mb-3">Users</h3>
                <p class="text-muted mb-4">Manage user accounts and permissions. View user activity and profiles.</p>
                <a href="{{ route('admin.users.index') }}" class="btn-primary-modern w-100">Manage Users</a>
            </div>
        </div>
    </div>
</div>

<!-- Real-time Notification Script -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    function updateOrderCount() {
        fetch("{{ route('admin.orders.count') }}")
            .then(res => res.json())
            .then(data => {
                const pendingOrders = document.getElementById('pending-orders');
                if (pendingOrders) {
                    pendingOrders.textContent = data.count || 0;
                }
            })
            .catch(err => console.error("Error fetching order count:", err));
    }

    // Initial load + refresh every 10 seconds
    updateOrderCount();
    setInterval(updateOrderCount, 10000);
});
</script>

@endsection
@endcan
