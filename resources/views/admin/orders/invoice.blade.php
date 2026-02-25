<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; font-size: 14px; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; border: 1px solid #eee; box-shadow: 0 0 10px rgba(0, 0, 0, .15); }
        .header { display: flex; justify-content: space-between; margin-bottom: 20px; }
        .header h1 { font-size: 24px; color: #0D7377; margin: 0; }
        .header .company-info { text-align: right; }
        .details { display: table; width: 100%; margin-bottom: 30px; }
        .details > div { display: table-cell; width: 50%; }
        .details > div:last-child { text-align: right; }
        table.items { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        table.items th, table.items td { padding: 10px; border-bottom: 1px solid #eee; text-align: left; }
        table.items th { background: #ECEEF1; }
        table.items td.right, table.items th.right { text-align: right; }
        .totals { float: right; width: 300px; }
        .totals table { width: 100%; }
        .totals table td { padding: 5px; }
        .totals table td:last-child { text-align: right; font-weight: bold; }
        .totals .grand-total { font-size: 18px; color: #0D7377; }
        .footer { text-align: center; margin-top: 50px; font-size: 12px; color: #777; border-top: 1px solid #eee; padding-top: 20px;}
        .clear { clear: both; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <div class="header">
            <div>
                <h1>Modern Touch BD</h1>
                <p>Industrial Furniture & Racking Solutions</p>
            </div>
            <div class="company-info" style="float: right; text-align:right;">
                <strong>Invoice #:</strong> {{ $order->order_number }}<br>
                <strong>Date:</strong> {{ $order->created_at->format('F d, Y') }}<br>
                <strong>Status:</strong> {{ ucfirst($order->payment_status) }}
            </div>
            <div class="clear"></div>
        </div>

        <div class="details">
            <div style="float:left; width:50%;">
                <strong>Bill To / Ship To:</strong><br>
                {{ $order->shipping_name }}<br>
                {{ $order->shipping_address }}<br>
                {{ $order->shipping_city }}, {{ $order->shipping_state }} {{ $order->shipping_postal }}<br>
                Phone: {{ $order->shipping_phone }}
            </div>
            <div style="float:right; width:50%; text-align:right;">
                <strong>Payment Method:</strong><br>
                {{ ucfirst(str_replace('_', ' ', $order->payment_method)) }}
            </div>
            <div class="clear"></div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Item Description</th>
                    <th class="right">Qty</th>
                    <th class="right">Price</th>
                    <th class="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product_name }}
                        @if($item->variant_name) <br><small>Variant: {{ $item->variant_name }}</small> @endif
                    </td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">Tk {{ number_format($item->price, 0) }}</td>
                    <td class="right">Tk {{ number_format($item->subtotal, 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <table>
                <tr>
                    <td>Subtotal:</td>
                    <td>Tk {{ number_format($order->subtotal, 0) }}</td>
                </tr>
                @if($order->discount > 0)
                <tr>
                    <td>Discount:</td>
                    <td style="color: green;">-Tk {{ number_format($order->discount, 0) }}</td>
                </tr>
                @endif
                <tr>
                    <td>Shipping:</td>
                    <td>Tk {{ number_format($order->shipping_cost, 0) }}</td>
                </tr>
                <tr class="grand-total">
                    <td>Grand Total:</td>
                    <td>Tk {{ number_format($order->total, 0) }}</td>
                </tr>
            </table>
        </div>
        <div class="clear"></div>

        <div class="footer">
            <p>Thank you for choosing Modern Touch BD!</p>
            <p>If you have any questions regarding this invoice, please contact support at info@moderntouchbd.com or call +880 1700-000000.</p>
        </div>
    </div>
</body>
</html>
