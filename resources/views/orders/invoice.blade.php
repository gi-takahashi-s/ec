<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>請求書 #{{ $order->order_number }}</title>
    <style>
        body {
            font-family: ipag, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .company-info {
            margin-bottom: 20px;
        }
        .document-title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
            text-align: center;
        }
        .invoice-details {
            margin-bottom: 20px;
        }
        .customer-info {
            margin-bottom: 20px;
        }
        .billing-info {
            margin-bottom: 30px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            width: 300px;
            float: right;
            margin-bottom: 30px;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="company-info">
                <h1>ECサイト株式会社</h1>
                <p>〒123-4567 東京都渋谷区テック1-2-3</p>
                <p>TEL: 03-1234-5678 / Email: info@ecsite.com</p>
            </div>
        </div>
        
        <div class="document-title">請求書</div>
        
        <div class="invoice-details">
            <p><strong>請求書番号:</strong> INV-{{ $order->order_number }}</p>
            <p><strong>発行日:</strong> {{ now()->format('Y年m月d日') }}</p>
            <p><strong>注文日:</strong> {{ $order->created_at->format('Y年m月d日') }}</p>
            <p><strong>支払期限:</strong> {{ $order->created_at->addDays(7)->format('Y年m月d日') }}</p>
        </div>
        
        <div class="customer-info">
            <h3>請求先:</h3>
            <p>{{ $order->user->name }} 様</p>
            <p>〒{{ $order->shippingAddress->postal_code }}</p>
            <p>{{ $order->shippingAddress->prefecture }}{{ $order->shippingAddress->city }}{{ $order->shippingAddress->address_line1 }}</p>
            @if ($order->shippingAddress->address_line2)
                <p>{{ $order->shippingAddress->address_line2 }}</p>
            @endif
            <p>TEL: {{ $order->shippingAddress->phone }}</p>
        </div>
        
        <h3>ご注文内容:</h3>
        <table>
            <thead>
                <tr>
                    <th>商品名</th>
                    <th>単価</th>
                    <th>数量</th>
                    <th>小計</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td class="text-right">{{ number_format($item->price) }}円</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">{{ number_format($item->subtotal) }}円</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        
        <div class="totals">
            <table>
                <tr>
                    <td>小計:</td>
                    <td class="text-right">{{ number_format($order->subtotal) }}円</td>
                </tr>
                <tr>
                    <td>消費税 (10%):</td>
                    <td class="text-right">{{ number_format($order->tax) }}円</td>
                </tr>
                <tr>
                    <td>送料:</td>
                    <td class="text-right">{{ number_format($order->shipping_fee) }}円</td>
                </tr>
                <tr>
                    <td><strong>合計:</strong></td>
                    <td class="text-right"><strong>{{ number_format($order->total) }}円</strong></td>
                </tr>
            </table>
        </div>
        
        <div class="clearfix"></div>
        
        <div class="billing-info">
            <h3>お支払い方法:</h3>
            <p>クレジットカード（Stripe決済）</p>
            <p>支払い状況: 
                @if($order->payment_status == 'pending')
                    未払い
                @elseif($order->payment_status == 'paid')
                    支払い済み
                @elseif($order->payment_status == 'failed')
                    失敗
                @else 
                    {{ $order->payment_status }}
                @endif
            </p>
        </div>
        
        <div class="footer">
            <p>本請求書は電子的に生成されています。</p>
            <p>お問い合わせは、カスタマーサポート（support@ecsite.com）までお願いいたします。</p>
        </div>
    </div>
</body>
</html> 