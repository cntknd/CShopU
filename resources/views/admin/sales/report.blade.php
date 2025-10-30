<!DOCTYPE html>
<html lang="en">
<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report - {{ $startDate }} to {{ $endDate }}</title>
    <style>
        @media print {
            @page {
                size: landscape;
                margin: 0.5cm;
            }
            .no-print {
                display: none;
            }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: white;
        }
        
    .university-header {
        text-align: center;
        margin-bottom: 25px;
        border-bottom: 2px solid #000;
        padding-bottom: 15px;
    }
    
    .university-header h1 {
        margin: 5px 0;
        font-size: 24px;
        font-weight: bold;
        letter-spacing: 1px;
    }
    
    .university-header h2 {
        margin: 5px 0;
        font-size: 20px;
        font-weight: normal;
        letter-spacing: 1px;
    }
    
    .report-title {
        margin: 8px 0;
        font-size: 28px;
        font-weight: bold;
        letter-spacing: 2px;
    }
    
    .header {
        text-align: center;
        margin-bottom: 20px;
    }
    
    .header h1 {
        margin: 5px 0;
        font-size: 18px;
        font-weight: bold;
    }
    
    .header p {
        margin: 2px 0;
        font-size: 14px;
    }
        
        .report-info {
            text-align: center;
            margin-bottom: 15px;
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 0 auto;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: right;
        }
        
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        
        td:first-child {
            text-align: left;
            font-weight: bold;
        }
        
        .section-header {
            background-color: #e0e0e0;
            text-align: center;
            font-weight: bold;
        }
        
        .print-btn {
            margin: 20px;
            text-align: center;
        }
        
        .print-btn button {
            padding: 12px 32px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }
        
        .print-btn button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }
        
        .print-btn button:active {
            transform: translateY(0);
        }
        
        .date-range {
            text-align: center;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
    <div class="no-print">
        <div style="margin-bottom: 10px;">
            <a href="{{ route('admin.sales.overview') }}" style="color: #800000; text-decoration: none; font-weight: bold;">← Back to Sales Overview</a>
        </div>
        <div class="print-btn">
            <button onclick="window.print()">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="6 9 6 2 18 2 18 9"></polyline>
                    <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                    <rect x="6" y="14" width="12" height="8"></rect>
                </svg>
                Print Report
            </button>
        </div>
        <div class="date-range">
            <form method="GET" action="{{ route('admin.sales.report') }}" style="display: inline-block;">
                <label>Start Date:</label>
                <input type="date" name="start_date" value="{{ $startDate }}" style="margin-right: 15px;">
                
                <label>End Date:</label>
                <input type="date" name="end_date" value="{{ $endDate }}" style="margin-right: 15px;">
                
                <button type="submit" style="padding: 5px 15px; background-color: #800000; color: white; border: none; border-radius: 3px; cursor: pointer;">
                    Filter
                </button>
            </form>
        </div>
    </div>
    
    <div class="university-header">
        <h1>CAGAYAN STATE UNIVERSITY</h1>
        <h2>APARRI CAMPUS</h2>
        <div class="report-title">INVENTORY OF SCHOOL UNIFORM</div>
    </div>
    
    <div class="header">
        <p>For the period: {{ date('F d, Y', strtotime($startDate)) }} to {{ date('F d, Y', strtotime($endDate)) }}</p>
        <p>Generated on: {{ date('F d, Y h:i A') }}</p>
    </div>
    
    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 20%;">ITEM</th>
                <th colspan="3" class="section-header">BEGINNING BALANCE</th>
                <th colspan="3" class="section-header">PURCHASES</th>
                <th colspan="3" class="section-header">CASH SALES</th>
                <th colspan="3" class="section-header">ENDING BALANCE</th>
            </tr>
            <tr>
                <th class="section-header">QTY</th>
                <th class="section-header">UNIT COST</th>
                <th class="section-header">TOTAL COST</th>
                <th class="section-header">QTY</th>
                <th class="section-header">UNIT COST</th>
                <th class="section-header">TOTAL COST</th>
                <th class="section-header">QTY</th>
                <th class="section-header">UNIT COST</th>
                <th class="section-header">TOTAL COST</th>
                <th class="section-header">QTY</th>
                <th class="section-header">UNIT COST</th>
                <th class="section-header">TOTAL COST</th>
            </tr>
        </thead>
        <tbody>
            @php
                $grandTotalBeginning = 0;
                $grandTotalPurchases = 0;
                $grandTotalSales = 0;
                $grandTotalEnding = 0;
            @endphp
            
            @foreach($reportData as $item)
                @php
                    // Initialize quantities with fallbacks to 0
                    $beginning_qty = $item['beginning_qty'] ?? 0;
                    $purchases_qty = $item['purchases_qty'] ?? 0;
                    $sales_qty = $item['total_ordered_qty'] ?? $item['sales_qty'] ?? 0;
                    
                    // Get unit cost with fallbacks
                    $unit_cost = $item['unit_cost'] ?? 0;
                    
                    // Calculate totals using the unit cost
                    $beginning_total = $beginning_qty * $unit_cost;
                    $purchases_total = $purchases_qty * $unit_cost;
                    $sales_total = $sales_qty * $unit_cost;
                    $ending_qty = $beginning_qty + $purchases_qty - $sales_qty;
                    $ending_total = $ending_qty * $unit_cost;

                    $grandTotalBeginning += $beginning_total;
                    $grandTotalPurchases += $purchases_total;
                    $grandTotalSales += $sales_total;
                    $grandTotalEnding += $ending_total;
                @endphp
                <tr>
                    <td>{{ $item['item'] }}</td>
                    <td>{{ number_format($beginning_qty, 0) }}</td>
                    <td>₱{{ number_format($unit_cost, 2) }}</td>
                    <td>₱{{ number_format($beginning_total, 2) }}</td>
                    <td>{{ number_format($purchases_qty, 0) }}</td>
                    <td>₱{{ number_format($unit_cost, 2) }}</td>
                    <td>₱{{ number_format($purchases_total, 2) }}</td>
                    <td>{{ number_format($sales_qty, 0) }}</td>
                    <td>₱{{ number_format($unit_cost, 2) }}</td>
                    <td>₱{{ number_format($sales_total, 2) }}</td>
                    <td>{{ number_format($ending_qty, 0) }}</td>
                    <td>₱{{ number_format($unit_cost, 2) }}</td>
                    <td>₱{{ number_format($ending_total, 2) }}</td>
                </tr>
            @endforeach
            
            <!-- Grand Total Row -->
            <tr style="font-weight: bold; background-color: #f0f0f0;">
                <td>TOTAL</td>
                <td></td>
                <td></td>
                <td>₱{{ number_format($grandTotalBeginning, 2) }}</td>
                <td></td>
                <td></td>
                <td>₱{{ number_format($grandTotalPurchases, 2) }}</td>
                <td></td>
                <td></td>
                <td>₱{{ number_format($grandTotalSales, 2) }}</td>
                <td></td>
                <td></td>
                <td>₱{{ number_format($grandTotalEnding, 2) }}</td>
            </tr>
        </tbody>
    </table>
    
    
</body>
</html>