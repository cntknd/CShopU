@extends('admin.layout')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<div class="max-w-4xl mx-auto px-4 py-6">
    <div class="mb-4 print:hidden">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
            ← Back to Orders
        </a>
    </div>

    <!-- Payslip Container -->
    <div class="bg-white shadow rounded overflow-hidden border" id="payslip">
        <div class="p-4 border-bottom text-center">
            <h2 class="h5 mb-1">PAYMENT / ORDER SLIP</h2>
            <small class="text-muted">Please fill quantities and unit costs where applicable</small>
        </div>

        <!-- Header Fields -->
        <div class="p-3">
            <div class="row mb-2">
                <div class="col-6">
                    <strong>ID NO.</strong>
                    <div class="border rounded p-2">#{{ $order->id }}</div>
                </div>
                <div class="col-6">
                    <strong>NAME</strong>
                    <div class="border rounded p-2">{{ $order->user->name ?? 'N/A' }}</div>
                </div>
            </div>
        </div>

        <!-- Table like the attached payslip -->
        <div class="p-3">
            <div class="table-responsive">
                <table class="table table-sm table-bordered mb-0">
                    <thead class="table-light text-uppercase small">
                        <tr>
                            <th style="width:8%;">UNIT</th>
                            <th>ITEM</th>
                            <th style="width:8%; text-align:center;">QTY</th>
                            <th style="width:12%; text-align:right;">U.COST</th>
                            <th style="width:12%; text-align:right;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php 
                            $grand = 0;
                            $supplier_grand = 0; 
                        @endphp
                        @foreach($order->orderItems ?? $order->items as $item)
                            @php
                                $unit = 'pc';
                                $qty = $item->quantity ?? 1;
                                $unitCost = $item->supplier_price ?? ($item->product->supplier_price ?? 0);
                                $rowTotal = $qty * $unitCost;
                                $supplier_grand += $rowTotal;
                            @endphp
                            <tr>
                                <td class="align-middle">{{ $unit }}</td>
                                <td class="align-middle">{{ $item->product->name ?? $item->name ?? 'Item' }}</td>
                                <td class="text-center align-middle">{{ $qty }}</td>
                                <td class="text-end align-middle">{{ $unitCost ? '₱' . number_format($unitCost,2) : '' }}</td>
                                <td class="text-end align-middle">{{ $rowTotal ? '₱' . number_format($rowTotal,2) : '' }}</td>
                            </tr>
                        @endforeach

                        {{-- Fill empty rows to keep format (optional) --}}
                        @for($i = ($order->orderItems->count() ?? $order->items->count()) ; $i < 10; $i++)
                            <tr>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            </tr>
                        @endfor
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>TOTAL SUPPLIER COST</strong></td>
                            <td class="text-end"><strong>₱{{ number_format($supplier_grand, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>TOTAL SALES PRICE</strong></td>
                            <td class="text-end"><strong>₱{{ number_format($order->total_price,2) }}</strong></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end"><strong>PROFIT</strong></td>
                            <td class="text-end"><strong>₱{{ number_format($order->total_price - $supplier_grand,2) }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <!-- Signature / Notes -->
        <div class="p-3">
            <div class="row mt-3">
                <div class="col-6">
                    <div style="height:60px; border-bottom:1px solid #ddd"></div>
                    <div class="text-muted small mt-2">Business Staff / Project Manager</div>
                </div>
                <div class="col-6 text-end">
                    <div class="text-muted small">Generated: {{ now()->format('F d, Y h:i A') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-4 d-flex gap-2 print:hidden">
        <button onclick="printPayslip()" class="btn btn-primary">
            <i class="bi bi-printer"></i> Print Payslip
        </button>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Done</a>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #payslip, #payslip * { visibility: visible; }
        #payslip { position: absolute; left:0; top:0; width:100%; padding:20px; }
        @page { size: A4; margin: 10mm; }
        * { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>

<script>
    function printPayslip() {
        window.print();
    }
    // Ctrl/Cmd+P shortcut to print
    document.addEventListener('keydown', function(e){
        if((e.ctrlKey || e.metaKey) && e.key === 'p'){
            e.preventDefault(); printPayslip();
        }
    });
</script>

@endsection