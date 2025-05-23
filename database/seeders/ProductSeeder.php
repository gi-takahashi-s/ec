<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // カテゴリーのスラグと名前のマッピング
        $categories = [
            'smartphones' => 'スマートフォン',
            'televisions' => 'テレビ',
            'audio-equipment' => 'オーディオ機器',
            'mens-wear' => 'メンズウェア',
            'womens-wear' => 'レディースウェア',
            'accessories' => 'アクセサリー',
            'beverages' => '飲料',
            'snacks' => 'スナック菓子',
            'novels' => '小説',
            'music-cds' => '音楽CD',
            'movie-dvd-bd' => '映画DVD/BD',
        ];

        // 各カテゴリーの商品
        foreach ($categories as $categorySlug => $categoryName) {
            $category = Category::where('slug', $categorySlug)->first();
            
            if (!$category) {
                continue;
            }

            // カテゴリーごとに5つの商品を作成
            for ($i = 1; $i <= 5; $i++) {
                $productName = "{$categoryName} サンプル商品 {$i}";
                $productSlug = Str::slug("{$categorySlug}-sample-{$i}");
                $basePrice = $this->getCategoryBasePrice($categorySlug);
                $price = $basePrice + (rand(1, 10) * 1000);
                
                // セール商品はランダムに設定
                $salePrice = (rand(1, 5) === 1) ? round($price * 0.8) : null;
                
                // 商品在庫はランダムに設定
                $stock = rand(0, 50);
                
                // おすすめ商品はランダムに設定
                $isFeatured = (rand(1, 5) === 1);
                
                // 商品コード
                $sku = strtoupper(substr($categorySlug, 0, 3)) . '-' . rand(1000, 9999);

                // 商品の説明文
                $description = "これは{$categoryName}カテゴリーのサンプル商品{$i}です。商品の詳細な説明がここに入ります。\n\n";
                $description .= "・高品質な素材を使用\n";
                $description .= "・使いやすいデザイン\n";
                $description .= "・長期間お使いいただける耐久性\n";
                $description .= "・安心の保証付き\n\n";
                $description .= "ぜひお試しください。";

                // 商品の特徴
                $features = "【商品の特徴】\n";
                $features .= "✓ 最新の技術を採用\n";
                $features .= "✓ 使いやすさを追求したデザイン\n";
                $features .= "✓ 環境に配慮した素材を使用\n";
                $features .= "✓ 多くのお客様から高評価をいただいています\n";

                // 商品の仕様
                $specifications = "【商品仕様】\n";
                $specifications .= "サイズ: 幅○○cm × 高さ○○cm × 奥行○○cm\n";
                $specifications .= "重量: ○○g\n";
                $specifications .= "素材: ○○○\n";
                $specifications .= "製造国: 日本\n";
                $specifications .= "保証期間: 購入日より1年間";

                // 商品データの作成
                $product = Product::create([
                    'name' => $productName,
                    'slug' => $productSlug,
                    'description' => $description,
                    'features' => $features,
                    'specifications' => $specifications,
                    'price' => $price,
                    'sale_price' => $salePrice,
                    'stock' => $stock,
                    'sku' => $sku,
                    'is_visible' => true,
                    'is_featured' => $isFeatured,
                    'image_path' => "images/no-image.png", // デフォルト画像
                    'category_id' => $category->id,
                ]);
            }
        }
    }

    /**
     * カテゴリーに応じたベース価格を取得
     */
    private function getCategoryBasePrice($categorySlug)
    {
        switch ($categorySlug) {
            case 'smartphones':
                return 50000;
            case 'televisions':
                return 80000;
            case 'audio-equipment':
                return 30000;
            case 'mens-wear':
            case 'womens-wear':
                return 5000;
            case 'accessories':
                return 3000;
            case 'beverages':
                return 200;
            case 'snacks':
                return 150;
            case 'novels':
                return 1500;
            case 'music-cds':
                return 2500;
            case 'movie-dvd-bd':
                return 4000;
            default:
                return 1000;
        }
    }
}
