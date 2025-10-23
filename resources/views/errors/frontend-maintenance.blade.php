<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メンテナンス中 - {{ config('app.name') }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #eff1f2;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #333;
        }

        .maintenance-container {
            background: white;
            padding: 40px 40px;
            text-align: center;
            max-width: 600px;
            width: 90%;
        }

        .maintenance-icon {
            width: 40px;
            height: 40px;
            margin: 0 auto 30px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 60px;
            color: #6c757d;
        }

        .maintenance-title {
            font-size: 2.1rem;
            font-weight: 700;
            color: #2c3e50;
            margin-bottom: 20px;
        }

        .maintenance-message {
            font-size: 1.2rem;
            color: #6c757d;
            line-height: 1.6;
            margin-bottom: 40px;
        }

        .maintenance-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 30px;
            margin-bottom: 30px;
        }

        .maintenance-info h3 {
            color: #495057;
            margin-bottom: 15px;
            font-size: 1.1rem;
        }

        .maintenance-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            text-align: left;
        }

        .detail-item {
            display: flex;
            flex-direction: column;
            margin-bottom: 20px;
        }

        .detail-label {
            font-size: 0.9rem;
            color: #6c757d;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 600;
            color: #495057;
        }

        .refresh-button {
            background: #667eea;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 50px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            text-decoration: none;
            display: inline-block;
        }

        .refresh-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }

        .footer-text {
            margin-top: 30px;
            font-size: 0.9rem;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .maintenance-container {
                padding: 40px 20px;
            }

            .maintenance-title {
                font-size: 2rem;
            }

            .maintenance-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>

<body>
    <div class="maintenance-container">

        <h1 class="maintenance-title">メンテナンス中</h1>

        <p class="maintenance-message">
            {!! nl2br(e($message ?? 'サイトメンテナンス中です。しばらくお待ちください。')) !!}
        </p>

        @if(isset($end_time) && !empty($end_time))
        <div class="detail-item">
            <span class="detail-label">終了予定時刻</span>
            <span class="detail-value">{{ date('Y年m月d日 H:i', strtotime($end_time)) }}</span>
        </div>
        @endif

        <button class="refresh-button" onclick="window.location.reload()">
            ページを更新
        </button>

        <p class="footer-text">
            ご不便をおかけして申し訳ございません。<br>
            メンテナンス完了まで今しばらくお待ちください。
        </p>
    </div>

    <script>
        // 手動リフレッシュのみ対応
    </script>
</body>

</html>