<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt - Sale #{{ $sale->sale_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 12px;
            max-width: 350px;
            margin: 0 auto;
            padding: 15px;
            background: #f8f9fa;
            color: #2d3748;
        }

        .receipt-container {
            background: white;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            text-align: center;
            padding: 15px;
            position: relative;
        }

        .header h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .receipt-id {
            position: absolute;
            top: 10px;
            right: 15px;
            background: rgba(255,255,255,0.2);
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: 600;
        }

        .content {
            padding: 15px;
        }

        .sale-info {
            margin-bottom: 15px;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 3px;
            border-left: 3px solid #28a745;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 4px;
            font-size: 11px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 15px;
        }

        .items-table th {
            background: #f8f9fa;
            color: #6c757d;
            font-weight: 600;
            padding: 6px 4px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }

        .items-table td {
            padding: 6px 4px;
            border-bottom: 1px solid #f1f3f4;
        }

        .totals-section {
            background: #f8f9fa;
            padding: 12px;
            border-radius: 3px;
            margin-bottom: 15px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 6px;
            font-size: 11px;
        }

        .total-row.final {
            border-top: 2px solid #28a745;
            padding-top: 8px;
            margin-top: 8px;
            font-size: 13px;
            font-weight: 700;
            color: #28a745;
        }

        .footer {
            text-align: center;
            border-top: 2px solid #28a745;
            padding: 12px;
            background: #f8f9fa;
        }

        .btn {
            padding: 8px 16px;
            border: none;
            border-radius: 3px;
            font-size: 11px;
            font-weight: 500;
            cursor: pointer;
            margin: 0 4px;
        }

        .btn-print {
            background: #28a745;
            color: white;
        }

        .btn-close {
            background: #6c757d;
            color: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <div class="header">
            <div class="receipt-id">{{ $sale->sale_number }}</div>
            <h1>{{ config('app.name', 'SAAS E-COMMERCE') }}</h1>
            <p>In-House Sales Receipt</p>
            <p>{{ now()->format('M d, Y • H:i:s') }}</p>
        </div>

        <div class="content">
            <div class="sale-info">
                <div class="info-row">
                    <span>Sale Number:</span>
                    <span>#{{ $sale->sale_number }}</span>
                </div>
                <div class="info-row">
                    <span>Customer:</span>
                    <span>
                        @if($sale->customer)
                            {{ $sale->customer->name }}
                            @if(isset($showCustomerId) && $showCustomerId)
                                (ID: {{ $sale->customer_id }})
                            @endif
                        @else
                            Walk-in Customer
                        @endif
                    </span>
                </div>
                @if($sale->customer && $sale->customer->phone)
                <div class="info-row">
                    <span>Phone:</span>
                    <span>{{ $sale->customer->phone }}</span>
                </div>
                @endif
                @if($sale->customer && $sale->customer->email)
                <div class="info-row">
                    <span>Email:</span>
                    <span>{{ $sale->customer->email }}</span>
                </div>
                @endif
                @if($sale->customer && $sale->customer->customerProfile && $sale->customer->customerProfile->address)
                <div class="info-row">
                    <span>Address:</span>
                    <span>{{ Str::limit($sale->customer->customerProfile->address, 30) }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span>Cashier:</span>
                    <span>{{ $sale->cashier->name ?? 'N/A' }}</span>
                </div>
                <div class="info-row">
                    <span>Date:</span>
                    <span>{{ $sale->sale_date->format('M d, Y H:i') }}</span>
                </div>
                <div class="info-row">
                    <span>Payment:</span>
                    <span>{{ ucfirst(str_replace('_', ' ', $sale->payment_method)) }}</span>
                </div>
            </div>

            <table class="items-table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleItems as $item)
                    <tr>
                        <td>
                            {{ Str::limit($item->product_name, 25) }}
                            @if($item->variation_name)
                                <br><small style="color: #6c757d;">{{ $item->variation_name }}</small>
                            @endif
                        </td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">Rs {{ number_format($item->unit_price, 2) }}</td>
                        <td style="text-align: right;">Rs {{ number_format($item->total_price, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="totals-section">
                <div class="total-row">
                    <span>Subtotal:</span>
                    <span>Rs {{ number_format($sale->subtotal, 2) }}</span>
                </div>
                @if($sale->discount_amount > 0)
                <div class="total-row">
                    <span>Discount:</span>
                    <span style="color: #dc3545;">-Rs {{ number_format($sale->discount_amount, 2) }}</span>
                </div>
                @endif
                @if($sale->tax_amount > 0)
                <div class="total-row">
                    <span>Tax:</span>
                    <span>Rs {{ number_format($sale->tax_amount, 2) }}</span>
                </div>
                @endif
                @if($sale->shipping_amount > 0)
                <div class="total-row">
                    <span>Shipping:</span>
                    <span>Rs {{ number_format($sale->shipping_amount, 2) }}</span>
                </div>
                @endif
                <div class="total-row final">
                    <span>TOTAL:</span>
                    <span>Rs {{ number_format($sale->total_amount, 2) }}</span>
                </div>
                <div class="total-row">
                    <span>Status:</span>
                    <span style="color: {{ $sale->payment_status === 'paid' ? '#28a745' : '#ffc107' }};">
                        {{ ucfirst($sale->payment_status) }}
                    </span>
                </div>
            </div>

            @if($sale->notes)
            <div style="background: #fff3cd; padding: 8px; border-radius: 3px; margin-bottom: 15px; font-size: 10px;">
                <strong>Notes:</strong> {{ $sale->notes }}
            </div>
            @endif
        </div>

        <div class="footer">
            <p style="font-size: 13px; font-weight: 600; color: #28a745; margin-bottom: 4px;">Thank you for your purchase!</p>
            <p style="font-size: 10px; color: #6c757d; margin-bottom: 10px;">{{ config('app.name') }} - In-House Sales</p>

            <div class="no-print">
                <button class="btn btn-print" onclick="window.print()">🖨️ Print Receipt</button>
                <button class="btn btn-close" onclick="window.close()">✖️ Close</button>
            </div>
        </div>
    </div>

    <!-- No auto-print script to avoid browser dialogs -->
</body>
</html>
