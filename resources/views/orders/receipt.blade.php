<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>領収書 #{{ $order->order_number }}</title>
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
        .receipt-details {
            margin-bottom: 20px;
        }
        .customer-info {
            margin-bottom: 20px;
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
        .receipt-box {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            text-align: center;
        }
        .receipt-amount {
            font-size: 18px;
            font-weight: bold;
            margin: 10px 0;
        }
        .stamp-area {
            border: 1px dashed #999;
            width: 100px;
            height: 100px;
            margin: 20px auto;
            text-align: center;
            line-height: 100px;
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
        
        <div class="document-title">領収書</div>
        
        <div class="receipt-box">
            <div>上記、正に領収いたしました。</div>
            <div class="receipt-amount">金額: {{ number_format($order->total) }}円</div>
            <div>但し {{ $order->created_at->format('Y年m月d日') }} のご注文（{{ $order->order_number }}）として</div>
        </div>
        
        <div class="receipt-details">
            <p><strong>領収書番号:</strong> REC-{{ $order->order_number }}</p>
            <p><strong>発行日:</strong> {{ now()->format('Y年m月d日') }}</p>
            <p><strong>支払日:</strong> {{ $order->paid_at ? $order->paid_at->format('Y年m月d日') : now()->format('Y年m月d日') }}</p>
        </div>
        
        <div class="customer-info">
            <h3>宛先:</h3>
            <p>{{ $order->user->name }} 様</p>
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
        
        <div class="stamp-area">
            印紙
        </div>
        
        <div class="footer">
            <p>本領収書は電子的に生成されています。</p>
            <p>お問い合わせは、カスタマーサポート（support@ecsite.com）までお願いいたします。</p>
        </div>
    </div>
</body>
</html> 