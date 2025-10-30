@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title">🧾 All User Orders</h1>
    <p class="page-subtitle">View and manage all customer orders from your CShopU store.</p>
</div>
<style>
/* ===== Modern Order Table Styling ===== */
.table-modern {
    border-collapse: separate;
    border-spacing: 0 12px; /* Adds breathing space between rows */
    width: 100%;
}

.table-modern thead th {
    background: #f8f9fa;
    color: #495057;
    text-transform: uppercase;
    font-size: 0.85rem;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
}

.table-modern tbody tr {
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
    transition: all 0.25s ease;
}

.table-modern tbody tr:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(128, 0, 0, 0.15);
}

.table-modern tbody td {
    padding: 1.25rem 1rem;
    vertical-align: middle;
    border-top: none;
    border-bottom: 1px solid #f1f1f1;
}

/* Accent line for each row */
.table-modern tbody tr td:first-child {
    border-left: 5px solid #800000;
    border-radius: 12px 0 0 12px;
}

/* Zebra effect for better readability */
.table-modern tbody tr:nth-child(even) {
    background: #fafafa;
}

/* Last row cleanup */
.table-modern tbody tr:last-child td {
    border-bottom: none;
}

/* Subtle hover highlight for first column */
.table-modern tbody tr:hover td:first-child {
    border-left-color: #a00000;
}

/* Compact mobile-friendly adjustments */
@media (max-width: 768px) {
    .table-modern thead {
        display: none;
    }

    .table-modern tbody tr {
        display: block;
        margin-bottom: 1rem;
        border-left: none;
        border-radius: 12px;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.08);
    }

    .table-modern tbody td {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #e9ecef;
    }

    .table-modern tbody td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #495057;
        text-transform: capitalize;
    }

    .table-modern tbody tr td:first-child {
        border-left: none;
    }
}
</style>

<style>
/* Modern action buttons for orders table */
.order-actions {
    display: flex;
    gap: .5rem;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
}

.order-actions .btn-action {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .45rem .65rem;
    border-radius: .5rem;
    font-size: .85rem;
    line-height: 1;
    border: 0;
    cursor: pointer;
    transition: transform .12s ease, box-shadow .12s ease, opacity .12s ease;
    box-shadow: 0 1px 2px rgba(16,24,40,0.04);
    background: #ffffff;
    color: #374151;
}

.order-actions .btn-action i {
    font-size: 1rem;
    line-height: 1;
}

.order-actions .btn-action:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 20px rgba(16,24,40,0.08);
}

/* Specific colors */
.order-actions .btn-confirm {
    background: linear-gradient(90deg,#059669,#10b981); /* green */
    color: #fff;
}
.order-actions .btn-receipt {
    background: linear-gradient(90deg,#6b7280,#111827); /* gray to dark */
    color: #fff;
}
.order-actions .btn-ship {
    background: linear-gradient(90deg,#2563eb,#1e40af); /* blue */
    color: #fff;
}
.order-actions .btn-cancel {
    background: linear-gradient(90deg,#ef4444,#dc2626); /* red */
    color: #fff;
}

.order-actions .btn-action[disabled], .order-actions .btn-action.disabled {
    opacity: .6;
    pointer-events: none;
    transform: none;
}

/* Responsive: stack actions on very small screens */
@media (max-width: 420px) {
    .order-actions { gap: .35rem; }
    .order-actions .btn-action { padding: .4rem .5rem; font-size: .8rem; }
}
</style>


<!-- Search Form -->
<div class="search-form mb-4">
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row">
        <div class="col-12">
            <div class="input-group">
                <input
                    type="text"
                    name="search"
                    value="{{ $search ?? '' }}"
                    placeholder="Search by Order ID, User Name, or Product..."
                    class="form-control form-control-modern search-input"
                >
                <button type="submit" class="btn btn-search">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </div>

        @if(isset($search) && $search)
            <div class="col-12 mt-2">
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle me-2"></i>Clear Search
                </a>
            </div>
        @endif
    </form>
</div>

@if(isset($search) && $search)
    <div class="alert-modern alert-info-modern">
        <i class="bi bi-info-circle me-2"></i>
        <strong>{{ $orders->count() }}</strong> result(s) found for "<strong>{{ $search }}</strong>"
    </div>
@endif

<!-- Order Statistics -->
@if($orders->count() > 0)
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="order-card p-4 text-center">
            <div class="text-2xl font-bold text-primary">{{ $orders->count() }}</div>
            <div class="text-sm text-muted">Total Orders</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="order-card p-4 text-center">
            <div class="text-2xl font-bold text-warning">{{ $orders->where('status', 'pending')->count() }}</div>
            <div class="text-sm text-muted">Pending</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="order-card p-4 text-center">
            <div class="text-2xl font-bold text-success">{{ $orders->where('status', 'confirmed')->count() }}</div>
            <div class="text-sm text-muted">Confirmed</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="order-card p-4 text-center">
            <div class="text-2xl font-bold text-info">{{ $orders->where('status', 'completed')->count() }}</div>
            <div class="text-sm text-muted">Completed</div>
        </div>
    </div>
</div>
@endif

<!-- Orders Table -->
<div class="order-card">
    <div class="table-responsive">
        <table class="table-modern table align-middle">
            <thead class="table-light">
                <tr>
                    <th class="fw-semibold">Order ID</th>
                    <th class="fw-semibold">User</th>
                    <th class="fw-semibold">Products</th>
                    <th class="fw-semibold">Status</th>
                    <th class="fw-semibold">Payment</th>
                    <th class="fw-semibold">Total</th>
                    <th class="fw-semibold">Date</th>
                    <th class="fw-semibold text-center">Actions</th>
                    <th class="fw-semibold text-center">Completed</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td class="fw-semibold">#{{ $order->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->user->name ?? 'N/A' }}</div>
                            <div class="text-muted small">{{ $order->user->email ?? '' }}</div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1">
                                    @php
                                        $items = $order->items ?? $order->orderItems ?? collect();
                                        $totalItems = $items->count();
                                    @endphp
                                    
                                @if($totalItems > 0)
                                    @foreach($items->take(2) as $item)
                                        <div class="d-flex align-items-center gap-2">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('images/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded" style="width: 32px; height: 32px; object-fit: cover;">
                                            @endif
                                            <div class="small">
                                                <div class="fw-semibold">{{ $item->product->name ?? 'N/A' }}</div>
                                                <div class="text-muted">
                                                    Qty: {{ $item->quantity }}
                                                    @if($item->size)
                                                        · Size: {{ $item->size }}
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    @if($totalItems > 2)
                                        <div class="small text-primary fw-semibold mt-1">
                                            +{{ $totalItems - 2 }} more item(s)
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small">No items</span>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="status-badge 
                                @if($order->status === 'pending') status-pending
                                @elseif($order->status === 'confirmed') status-confirmed
                                @elseif($order->status === 'shipped') status-shipped
                                @elseif($order->status === 'delivered') status-delivered
                                @elseif($order->status === 'completed') status-delivered
                                @elseif($order->status === 'cancelled') status-cancelled
                                @endif">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td>
                            @if($order->status === 'confirmed' && !$order->paid_at)
                                @php
                                    $timeRemaining = $order->getTimeRemainingToPay();
                                    $deadline = $order->confirmed_at ? $order->confirmed_at->copy()->addHours(24) : null;
                                    $isOverdue = $deadline && $deadline->isPast();
                                @endphp
                                @if($isOverdue)
                                    <span class="status-badge status-cancelled">OVERDUE</span>
                                    <div class="small text-danger mt-1">Auto-cancel pending</div>
                                @else
                                    <span class="status-badge status-pending">UNPAID</span>
                                    <div class="small text-muted mt-1">{{ $timeRemaining }} remaining</div>
                                @endif
                            @elseif($order->paid_at)
                                <span class="status-badge status-confirmed">PAID</span>
                                <div class="small text-muted mt-1">{{ $order->paid_at->format('M d, Y') }}</div>
                            @else
                                <span class="small text-muted">-</span>
                            @endif
                        </td>
                        <td class="fw-semibold">₱{{ number_format($order->total_price ?? $order->total ?? 0, 2) }}</td>
                        <td class="small text-muted">
                            {{ $order->created_at->format('M d, Y') }}<br>
                            <span class="text-muted">{{ $order->created_at->format('h:i A') }}</span>
                        </td>
                        <td class="text-center">
                            <div class="order-actions">
                                @if($order->status === 'pending')
                                    <form method="POST" action="{{ route('admin.orders.confirm', $order->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-confirm">
                                            <i class="bi bi-check-lg me-1"></i>Confirm
                                        </button>
                                    </form>
                                @elseif($order->status === 'confirmed')
                                    @if(!$order->paid_at)
                                        <form method="POST" action="{{ route('admin.orders.mark-paid', $order->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn-action btn-confirm" title="Mark as paid">
                                                <i class="bi bi-cash-coin me-1"></i>Mark Paid
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('admin.orders.receipt', $order->id) }}" class="btn-action btn-receipt">
                                        <i class="bi bi-receipt me-1"></i>payslip
                                    </a>
                                @else
                                    <span class="text-muted small">No actions</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="order-actions">
                                @if($order->status !== 'completed' && in_array($order->status, ['confirmed','shipped','delivered']))
                                    <form method="POST" action="{{ route('admin.orders.complete', $order->id) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-action btn-ship" title="Mark as completed">
                                            <i class="bi bi-flag-fill me-1"></i>Complete
                                        </button>
                                    </form>
                                @elseif($order->status === 'completed')
                                    <a href="{{ route('admin.orders.receipt', $order->id) }}" class="btn-action btn-receipt">
                                        <i class="bi bi-receipt me-1"></i>Receipt
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </div>
                        </td>
                        </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            @if(isset($search) && $search)
                                <div class="text-muted">
                                    <i class="bi bi-search display-4 mb-3"></i>
                                    <p class="h5">No orders found</p>
                                    <p class="small">Try searching with different keywords</p>
                                </div>
                            @else
                                <div class="text-muted">
                                    <i class="bi bi-cart display-4 mb-3"></i>
                                    <p class="h5">No orders yet</p>
                                    <p class="small">Orders will appear here when customers place them</p>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection