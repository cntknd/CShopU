@extends('layouts.Users.app')

@section('content')
    {{-- Print-specific styles --}}
    <style>
        @media print {
            html, body {
                height: 100%;
                margin: 0;
                padding: 0;
            }
            body * { visibility: hidden; }
            .print-content, .print-content * { visibility: visible; }
            .print-content { 
                position: absolute; 
                left: 0; 
                top: 0; 
                width: 100%; 
                padding: 20px; 
            }
            .no-print { display: none !important; }
            @page { 
                size: A4 portrait; 
                margin: 10mm; 
            }
        }

        .payslip-table {
            width: 100%;
            border-collapse: collapse;
            font-family: Arial, sans-serif;
        }

        .payslip-table td,
        .payslip-table th {
            border: 1px solid #000;
            padding: 4px 6px;
            font-size: 11px;
            line-height: 1.2;
        }

        .payslip-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }

        .unit-col { width: 8%; text-align: center; }
        .item-col { width: 40%; }
        .qty-col { width: 10%; text-align: center; }
        .cost-col { width: 18%; text-align: center; }
        .total-col { width: 24%; text-align: center; }

        .header-title {
            text-align: center;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 15px;
            text-transform: uppercase;
        }

        .info-row {
            margin-bottom: 10px;
        }

        .info-label {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 2px;
        }

        .info-value {
            border-bottom: 1px solid #000;
            min-height: 20px;
            padding: 2px 5px;
            font-size: 11px;
        }

        .total-row td {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .signature-section {
            margin-top: 20px;
            text-align: center;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 280px;
            margin: 0 auto;
            padding-top: 4px;
            font-size: 11px;
        }
    </style>

    {{-- Back button --}}
    <div class="max-w-4xl mx-auto p-4 no-print">
        <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
            ← Back to Orders
        </a>
        <button onclick="window.print()" class="ml-2 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
            🖨️ Print
        </button>
    </div>

    {{-- Payslip Content --}}
    <div class="max-w-4xl mx-auto bg-white p-8 print-content">
        {{-- Header --}}
        <div class="header-title">PAYMENT / ORDER SLIP</div>

        {{-- ID and Name --}}
        <div class="grid grid-cols-2 gap-6 mb-4">
            <div class="info-row">
                <div class="info-label">ID NO.</div>
                <div class="info-value">#{{ $order->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">NAME:</div>
                <div class="info-value">
                    {{ optional($order->user)->username ?? (trim((optional($order->user)->first_name ?? '') . ' ' . (optional($order->user)->middle_initial ?? '') . ' ' . (optional($order->user)->last_name ?? '')) ?: ('User #' . $order->user_id)) }}
                </div>
            </div>
        </div>

        {{-- Items Table --}}
        <table class="payslip-table">
            <thead>
                <tr>
                    <th class="unit-col">UNIT</th>
                    <th class="item-col">ITEM</th>
                    <th class="qty-col">QTY</th>
                    <th class="cost-col">U.COST</th>
                    <th class="total-col">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                {{-- Actual order items --}}
                @php 
                    $grand_total = 0;
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
                    $itemIndex = 0;
                @endphp

                @foreach($order->items as $item)
                    <tr>
                        <td class="unit-col">pc</td>
                        <td class="item-col">{{ $item->product->name }}</td>
                        <td class="qty-col">{{ $item->quantity }}</td>
                        <td class="cost-col">{{ number_format($item->price, 2) }}</td>
                        <td class="total-col">{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                    @php 
                        $grand_total += ($item->price * $item->quantity);
                        $itemIndex++;
                    @endphp
                @endforeach

                {{-- Fill remaining rows with template items --}}
                @for($i = $itemIndex; $i < 25; $i++)
                    <tr>
                        <td class="unit-col">pc</td>
                        <td class="item-col">{{ $items[$i] ?? '' }}</td>
                        <td class="qty-col"></td>
                        <td class="cost-col"></td>
                        <td class="total-col"></td>
                    </tr>
                @endfor

                {{-- Total row --}}
                <tr class="total-row">
                    <td colspan="4" style="text-align: right;">TOTAL</td>
                    <td class="total-col">{{ number_format($grand_total, 2) }}</td>
                </tr>
            </tbody>
        </table>

        {{-- Signature section --}}
        <div class="signature-section">
            <div style="margin-bottom: 40px;"></div>
            <div class="signature-line">
                Business Staff / Project Manager
            </div>
        </div>
    </div>

    @if(isset($download) && $download)
        <script>
            window.onload = function() {
                window.print();
            }
        </script>
    @endif
@endsection