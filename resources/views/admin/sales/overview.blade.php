@extends('layouts.Admin.app')
@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<div class="page-header">
    <h1 class="page-title">💰 Sales Overview</h1>
    <p class="page-subtitle">Track your business performance and revenue analytics.</p>
    <div style="text-align: right; margin-top: -50px;">
        <a href="{{ route('admin.sales.report') }}" class="modern-print-btn">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="6 9 6 2 18 2 18 9"></polyline>
                <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                <rect x="6" y="14" width="12" height="8"></rect>
            </svg>
            View Printable Report
        </a>
    </div>
</div>
<style>
    .sales-container{background-color:#fff;border-radius:15px;padding:2rem;box-shadow:0 4px 15px rgba(0,0,0,.1);margin-bottom:2rem}
    .stat-box{background:linear-gradient(135deg,#f8f9fa 0%,#e9ecef 100%);border-radius:15px;padding:1.5rem;text-align:center;box-shadow:0 4px 15px rgba(0,0,0,.1);border-left:4px solid #800000;transition:all .3s ease}
    .stat-box:hover{transform:translateY(-2px);box-shadow:0 8px 25px rgba(0,0,0,.15)}
    .stat-value{font-size:2.5rem;font-weight:700;color:#800000;margin-bottom:.5rem}
    .stat-label{color:#666;font-weight:500}
    .chart-container{margin-top:2rem}
    
    .modern-print-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 28px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        text-decoration: none;
        border-radius: 10px;
        font-weight: 600;
        font-size: 15px;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
        transition: all 0.3s ease;
        border: none;
    }
    
    .modern-print-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        color: white;
    }
    
    .modern-print-btn:active {
        transform: translateY(0);
    }
    
    .modern-print-btn svg {
        flex-shrink: 0;
    }
</style>
<div class="row g-4 mb-4">
    <div class="col-lg-3 col-md-6">
        <div class="stat-box">
            <div class="stat-value">₱{{ number_format($totalSales, 2) }}</div>
            <div class="stat-label">Total Sales</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-box">
            <div class="stat-value">{{ $totalOrders }}</div>
            <div class="stat-label">Total Orders</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-box">
            <div class="stat-value text-success">{{ $orderStatusCount['confirmed'] }}</div>
            <div class="stat-label">Confirmed</div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6">
        <div class="stat-box">
            <div class="stat-value text-primary">{{ $orderStatusCount['completed'] }}</div>
            <div class="stat-label">Completed</div>
        </div>
    </div>
</div>

{{-- 
|------------------------------------|
| MOVED: Top Products Section (Start) |
|------------------------------------|
--}}
<div class="sales-container">
    <h2 class="h5 fw-bold mb-4">🔥 Top-Selling Products</h2>
    <div class="table-responsive">
        <table class="table-modern table align-middle">
            <thead class="table-light">
                <tr>
                    <th class="fw-semibold">Product</th>
                    <th class="fw-semibold text-center">Total Sold</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($topProducts as $product)
                    <tr>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td class="text-center fw-semibold">{{ $product->total_sold }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
{{-- 
|------------------------------------|
| MOVED: Top Products Section (End)   |
|------------------------------------|
--}}


<div class="sales-container">
    <h2 class="h5 fw-bold mb-4">📅 Monthly Sales</h2>
    <canvas id="monthlySalesChart"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Ensure x-axis shows January -> December and that our data arrays have 12 entries.
    const monthNames = ['January','February','March','April','May','June','July','August','September','October','November','December'];

    // Build maps from server-provided collections: { monthNumber: total }
    // Expecting months to be numeric (1-12). Using pluck('total','month') creates a month=>total map.
    const monthlyDataMap = @json($monthlySales->pluck('total','month'));
    const categoryDataMap = @json(isset($categoryMonthlySales) ? $categoryMonthlySales->pluck('total','month') : []);

    // Produce arrays of length 12 (Jan..Dec) with zeros for missing months
    const monthlyTotals = [];
    const categoryTotals = [];
    for (let m = 1; m <= 12; m++) {
        monthlyTotals.push(Number(monthlyDataMap[m] ?? 0));
        categoryTotals.push(Number(categoryDataMap[m] ?? 0));
    }

    const monthlyLabels = monthNames.slice();

    const ctx1 = document.getElementById('monthlySalesChart').getContext('2d');
    // Render grouped bars: overall monthly sales (dark red) and category sales (gray)
    new Chart(ctx1, {
        type: 'bar',
            data: {
            labels: monthlyLabels,
            datasets: [
                {
                    label: 'Monthly Sales (₱)',
                    data: monthlyTotals,
                    backgroundColor: 'rgba(128, 0, 0, 0.9)',
                    borderColor: 'rgba(128, 0, 0, 1)',
                    borderWidth: 1,
                    // wider bar
                    barPercentage: 0.8,
                    categoryPercentage: 0.7,
                },
                {
                    label: 'Category Sales',
                    data: categoryTotals,
                    backgroundColor: 'rgba(107, 114, 128, 0.85)',
                    borderColor: 'rgba(107, 114, 128, 1)',
                    borderWidth: 1,
                    // slightly narrower so it appears beside the main bar
                    barPercentage: 0.5,
                    categoryPercentage: 0.7,
                }
            ]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true, position: 'top' },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.dataset.label || '';
                            const value = context.parsed.y;
                            return label ? (label + ': ₱' + Number(value).toLocaleString()) : '₱' + Number(value).toLocaleString();
                        }
                    }
                }
            },
            scales: {
                x: { stacked: false },
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) { return '₱' + value.toLocaleString(); }
                    }
                }
            }
        }
    });
});
</script>

@endsection