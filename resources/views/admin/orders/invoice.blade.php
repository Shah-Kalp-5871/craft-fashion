<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 14px;
            color: #333;
            line-height: 1.5;
            margin: 0;
            padding: 40px;
        }
        @media print {
            body {
                padding: 0;
            }
            .no-print {
                display: none;
            }
        }
        .invoice-container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a1a;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .company-info h1 {
            margin: 0;
            font-size: 24px;
        }
        .invoice-details {
            text-align: right;
        }
        .invoice-details h2 {
            margin: 0;
            color: #primary;
            font-size: 32px;
        }
        .addresses {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }
        .address-box h3 {
            margin-top: 0;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
            text-transform: uppercase;
            color: #666;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background: #f9f9f9;
            text-align: left;
            padding: 12px;
            border-bottom: 2px solid #eee;
            text-transform: uppercase;
            font-size: 12px;
            color: #666;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
        }
        .summary {
            display: flex;
            justify-content: flex-end;
        }
        .summary-table {
            width: 300px;
        }
        .summary-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .summary-row.total {
            font-weight: bold;
            font-size: 18px;
            border-top: 2px solid #333;
            margin-top: 10px;
            padding-top: 15px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            color: #999;
            font-size: 12px;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }
        .print-btn {
            background: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 14px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: right; max-width: 800px; margin: 0 auto;">
        <button class="print-btn" onclick="window.print()">Print Invoice</button>
    </div>

    <div class="invoice-container">
        <div class="header">
            <div class="company-info">
                <div class="logo">{{ config('constants.SITE_NAME', 'CRAFT FASHION') }}</div>
                <p>
                    123 Fashion Street, New Delhi, India<br>
                    Phone: +91 98765 43210<br>
                    Email: support@craftfashion.com
                </p>
            </div>
            <div class="invoice-details">
                <h2>INVOICE</h2>
                <p>
                    <strong>Order Number:</strong> {{ $order->order_number }}<br>
                    <strong>Date:</strong> {{ $order->created_at->format('M d, Y') }}<br>
                    <strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}<br>
                    <strong>Payment Method:</strong> {{ strtoupper($order->payment_method) }}
                </p>
            </div>
        </div>

        <div class="addresses">
            <div class="address-box">
                <h3>Billing To</h3>
                <p>
                    <strong>{{ $order->billing_address['name'] ?? 'N/A' }}</strong><br>
                    {{ $order->billing_address['address'] ?? '' }}<br>
                    {{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} - {{ $order->billing_address['pincode'] ?? '' }}<br>
                    {{ $order->billing_address['country'] ?? '' }}<br>
                    Phone: {{ $order->billing_address['phone'] ?? $order->billing_address['mobile'] ?? 'N/A' }}
                </p>
            </div>
            <div class="address-box">
                <h3>Shipping To</h3>
                <p>
                    <strong>{{ $order->shipping_address['name'] ?? 'N/A' }}</strong><br>
                    {{ $order->shipping_address['address'] ?? '' }}<br>
                    {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} - {{ $order->shipping_address['pincode'] ?? '' }}<br>
                    {{ $order->shipping_address['country'] ?? '' }}<br>
                    Phone: {{ $order->shipping_address['phone'] ?? $order->shipping_address['mobile'] ?? 'N/A' }}
                </p>
            </div>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>Item</th>
                    <th>Price</th>
                    <th style="text-align: center;">Qty</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                    <tr>
                        <td>
                            <strong>{{ $item->product->name ?? 'Product' }}</strong>
                            <br>
                            <small>
                                @if($item->variant)
                                    SKU: {{ $item->variant->sku }}
                                    @foreach($item->variant->attributes as $attr)
                                        | {{ $attr->attribute->name }}: {{ $attr->value }}
                                    @endforeach
                                @endif
                            </small>
                        </td>
                        <td>{{ number_format($item->price, 2) }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-table">
                <div class="summary-row">
                    <span>Subtotal:</span>
                    <span>{{ number_format($order->subtotal, 2) }}</span>
                </div>
                <div class="summary-row">
                    <span>Shipping:</span>
                    <span>{{ number_format($order->shipping_total, 2) }}</span>
                </div>
                @if($order->tax_total > 0)
                    <div class="summary-row">
                        <span>Tax:</span>
                        <span>{{ number_format($order->tax_total, 2) }}</span>
                    </div>
                @endif
                @if($order->discount_total > 0)
                    <div class="summary-row">
                        <span>Discount:</span>
                        <span>-{{ number_format($order->discount_total, 2) }}</span>
                    </div>
                @endif
                <div class="summary-row total">
                    <span>Grand Total:</span>
                    <span>{{ $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for shopping with {{ config('constants.SITE_NAME', 'CRAFT FASHION') }}!</p>
            <p>This is a computer generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
