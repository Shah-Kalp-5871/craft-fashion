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
            @php 
                $store = \App\Helpers\SettingsHelper::storeInfo();
                $logoUrl = \App\Helpers\SettingsHelper::get('logo_url');
            @endphp
            <div class="company-info">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="{{ $store['name'] }}" style="max-height: 60px; margin-bottom: 10px;">
                @else
                    <div class="logo">{{ $store['name'] }}</div>
                @endif
                <p>
                    {{ $store['address'] }}<br>
                    Phone: {{ $store['phone'] }}<br>
                    Email: {{ $store['email'] }}
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
                    @if(!empty($order->billing_address['address2']))
                        {{ $order->billing_address['address2'] }}<br>
                    @endif
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
                    @if(!empty($order->shipping_address['address2']))
                        {{ $order->shipping_address['address2'] }}<br>
                    @endif
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
                            <strong>{{ $item->product_name ?? ($item->product->name ?? 'Product') }}</strong>
                            <br>
                            <small>
                                SKU: {{ $item->sku ?? ($item->variant->sku ?? 'N/A') }}
                                @if(!empty($item->attributes) && is_array($item->attributes))
                                    @foreach($item->attributes as $key => $value)
                                        | {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ is_array($value) ? implode(', ', $value) : $value }}
                                    @endforeach
                                @elseif($item->variant && $item->variant->attributes)
                                    @foreach($item->variant->attributes as $attr)
                                        | {{ $attr->attribute->name }}: {{ $attr->value }}
                                    @endforeach
                                @endif
                            </small>
                        </td>
                        <td>{{ number_format($item->unit_price ?? $item->price ?? 0, 2) }}</td>
                        <td style="text-align: center;">{{ $item->quantity }}</td>
                        <td style="text-align: right;">{{ number_format(($item->unit_price ?? $item->price ?? 0) * $item->quantity, 2) }}</td>
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
                    <span>{{ $store['currency_symbol'] ?? $order->currency }} {{ number_format($order->grand_total, 2) }}</span>
                </div>
            </div>
        </div>

        <div class="footer">
            <p>Thank you for shopping with {{ $store['name'] }}!</p>
            <p>This is a computer generated invoice and does not require a signature.</p>
        </div>
    </div>
</body>
</html>
