# ECシステム (Laravel E-Commerce Platform)

フルスタックのオンラインショップシステム - Laravel 9.x ベース

## 概要

このプロジェクトは、Laravel 9.xフレームワークをベースにした本格的なECサイトシステムです。顧客向けのフロントエンド機能と、管理者向けの管理画面を備えています。

## 主な機能

### フロントエンド（顧客向け）

- **商品管理**
  - 商品一覧・詳細表示
  - カテゴリー別表示
  - 商品検索機能
  - 注目商品・新着商品表示
  - 在庫状況・セール価格表示

- **ショッピングカート**
  - カート追加・変更・削除
  - カートクリア機能

- **会員機能**
  - 新規会員登録・ログイン
  - プロフィール編集
  - 配送先住所管理（複数登録可能）
  - 注文履歴確認

- **注文・決済**
  - 注文フロー（カート→配送先→配送方法→決済方法→確認→完了）
  - 複数の決済方法対応
    - クレジットカード（Stripe）
    - 銀行振込
    - 代金引換
  - 請求書・領収書のPDF発行

### 管理画面（管理者向け）

- **ダッシュボード**
  - 売上サマリー
  - 注文状況
  - システム概要

- **商品管理**
  - 商品登録・編集・削除
  - 商品画像管理
  - 在庫管理
  - セール価格設定

- **カテゴリー管理**
  - カテゴリー登録・編集・削除
  - 階層管理

- **注文管理**
  - 注文一覧・詳細確認
  - ステータス変更
  - 発送処理
  - 銀行振込確認処理

- **ユーザー管理**
  - 会員情報管理
  - 管理者権限設定

- **売上レポート**
  - 商品別売上集計
  - 期間別売上集計（月別、日別）

- **ショップ設定**
  - 基本情報設定（ショップ名、連絡先、営業時間、URL）
  - 配送設定（配送業者、都道府県別配送料金、時間設定）
  - 決済設定（クレジットカード、銀行振込、代金引換）
  - 法的文書設定（特定商取引法、プライバシーポリシー）

- **メール設定**
  - メールテンプレート管理（注文受付、発送通知、会員関連）
  - 送信有効/無効切り替え

- **システム管理**
  - メンテナンスモード切り替え
  - ログイン履歴・セキュリティ監視
  - IPアドレス制限

## 技術スタック

| 項目 | 技術 |
|------|------|
| **フレームワーク** | Laravel 9.52.20 |
| **PHP** | 8.0.2以上 |
| **認証** | Laravel Breeze |
| **決済** | Stripe |
| **PDF生成** | DomPDF (barryvdh/laravel-dompdf) |
| **Redis** | Predis |
| **フロントエンド** | Tailwind CSS, Alpine.js, Vite |
| **データベース** | MySQL/MariaDB |

## 必要要件

- PHP >= 8.0.2
- Composer
- Node.js & npm
- MySQL 5.7+ または MariaDB 10.3+
- Redis（オプション）

## インストール

### 1. リポジトリをクローン

```bash
git clone <repository-url>
cd ec
```

### 2. 依存関係のインストール

```bash
# Composer パッケージのインストール
composer install

# npm パッケージのインストール
npm install
```

### 3. 環境設定

```bash
# .envファイルを作成
cp .env.example .env

# アプリケーションキーを生成
php artisan key:generate
```

### 4. データベース設定

`.env`ファイルを編集してデータベース接続情報を設定します。

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ec_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. データベースマイグレーション

```bash
# マイグレーションを実行
php artisan migrate

# シーダーを実行（オプション）
php artisan db:seed
```

### 6. ストレージリンクの作成

```bash
php artisan storage:link
```

または、MAMPなどの環境では：

```bash
php create_storage_link.php
```

### 7. フロントエンドのビルド

```bash
# 開発環境
npm run dev

# 本番環境
npm run build
```

### 8. アプリケーションの起動

```bash
php artisan serve
```

ブラウザで `http://localhost:8000` にアクセスしてください。

## Stripe設定（決済機能を使用する場合）

`.env`ファイルにStripeのAPIキーを設定します。

```env
STRIPE_KEY=your_publishable_key
STRIPE_SECRET=your_secret_key
```

## 管理者アカウント

初回セットアップ後、管理者アカウントを作成してください。

管理画面URL: `http://localhost:8000/admin`

## セキュリティ機能

- CSRF保護
- パスワードハッシュ化
- セッション管理
- IPアドレス制限（フロントエンド・管理画面）
- 管理者権限管理
- ログイン履歴監視
- メンテナンスモード

## ディレクトリ構造

```
ec/
├── app/
│   ├── Console/         # Artisanコマンド
│   ├── Helpers/         # ヘルパー関数
│   ├── Http/
│   │   ├── Controllers/ # コントローラー
│   │   └── Middleware/  # ミドルウェア
│   ├── Models/          # Eloquentモデル
│   ├── Notifications/   # 通知クラス
│   ├── Providers/       # サービスプロバイダー
│   └── Services/        # ビジネスロジック
├── config/              # 設定ファイル
├── database/
│   ├── migrations/      # マイグレーション
│   └── seeders/         # シーダー
├── public/              # 公開ディレクトリ
│   └── images/          # 公開画像
├── resources/
│   └── views/           # Bladeテンプレート
├── routes/              # ルート定義
└── storage/
    ├── app/public/      # アップロードファイル
    └── fonts/           # PDFフォント
```

## 開発

### コーディングスタイル

このプロジェクトでは Laravel Pint を使用してコーディングスタイルを統一しています。

```bash
./vendor/bin/pint
```

### キャッシュのクリア

```bash
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear
```

### メンテナンスモード

```bash
# メンテナンスモードを有効化
php artisan down

# メンテナンスモードを無効化
php artisan up
```

## トラブルシューティング

### ストレージリンクエラー

シンボリックリンクが作成できない場合は、`create_storage_link.php` を使用してください。

### パーミッションエラー

```bash
chmod -R 775 storage bootstrap/cache
```

### Composer/npmエラー

```bash
# Composerのキャッシュをクリア
composer clear-cache
composer install

# npmのキャッシュをクリア
npm cache clean --force
npm install
```

## 拡張性

このシステムは以下のような拡張が可能です：

- メールテンプレートのカスタマイズ
- 決済方法の追加
- 配送方法の追加
- 多言語対応
- 在庫自動発注機能
- 外部システムとのAPI連携

## ライセンス

このプロジェクトはMITライセンスの下で公開されています。

## サポート

問題が発生した場合や機能要望がある場合は、Issueを作成してください。

---

**開発者向け**: 詳細な機能一覧については `機能一覧.md` を参照してください。
