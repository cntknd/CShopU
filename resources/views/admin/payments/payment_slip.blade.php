@extends('layouts.Admin.app')

@section('content')
<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <div class="text-center mb-4">
                <h4 class="font-weight-bold">PAYMENT/ORDER SLIP</h4>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <p><strong>ID NO:</strong> {{ $order->id ?? '_____________' }}</p>
                    <p><strong>NAME:</strong> {{ $order->user->name ?? '_____________' }}</p>
                </div>
                <div class="col-md-6 text-end">
                    <p><strong>DATE:</strong> {{ $order->created_at ? $order->created_at->format('m/d/Y') : '_____________' }}</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 10%;">UNIT</th>
                            <th style="width: 40%;">ITEM</th>
                            <th class="text-center" style="width: 15%;">QTY</th>
                            <th class="text-end" style="width: 15%;">U.COST</th>
                            <th class="text-end" style="width: 20%;">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Standard Items List based on the image -->
                        @php
                            $items = [
                                'PE T-shirt',
                                'PE Jogging pants',
                                'NSTP T-shirt',
                                'POLO with Logo',
                                'CHM ORG.',
                                'CICS ORG.',
                                'CCJE ORG.',
                                'CBEA ORG.',
                                'CIT ORG.',
                                'CTED ORG.',
                                'CFAS ORG.',
                                'ID Strap',
                                'Bomber Jacket',
                                'Vehicle Pass',
                                'Coffee Mug',
                                'Customized Badge',
                                'Customized Pen',
                                'Hoodie Jacket',
                                'Insulated Water Tumbler',
                                'Keychain',
                                'Paper Bag',
                                'Paper Weight',
                                'Ranger Hat',
                                'Tote Bag',
                                'Hawks T-shirt'
                            ];
                            // Default unit is 'pc' for all items
                            $unit = 'pc';
                        @endphp

                        @foreach($items as $item)
                        <tr>
                            <td class="text-center">{{ $unit }}</td>
                            <td>{{ $item }}</td>
                            <td class="text-center">{{ isset($order->items[$item]) ? $order->items[$item]['quantity'] : '' }}</td>
                            <td class="text-end">{{ isset($order->items[$item]) ? '₱' . number_format($order->items[$item]['supplier_price'], 2) : '' }}</td>
                            <td class="text-end">{{ isset($order->items[$item]) ? '₱' . number_format($order->items[$item]['quantity'] * $order->items[$item]['supplier_price'], 2) : '' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="text-end"><strong>TOTAL COST</strong></td>
                            <td class="text-end"><strong>{{ isset($order->items) ? '₱' . number_format(collect($order->items)->sum(function($item) { return $item['quantity'] * $item['supplier_price']; }), 2) : '' }}</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="row mt-4">
                <div class="col-md-6">
                    <p class="mb-4"><strong>Business Staff/Project Manager:</strong> _________________________</p>
                </div>
            </div>

            <!-- Print Button -->
            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-primary">Print Slip</button>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        .btn-primary {
            display: none;
        }
        .card {
            border: none !important;
        }
        .container {
            width: 100% !important;
            max-width: none !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        body {
            padding: 0 !important;
            margin: 0 !important;
        }
        table {
            width: 100% !important;
        }
        th, td {
            padding: 5px !important;
        }
    }
    
    table {
        border-collapse: collapse;
        width: 100%;
    }
    
    th, td {
        border: 1px solid black;
        padding: 8px;
    }
    
    th {
        background-color: #f8f9fa;
    }
</style>
@endsection