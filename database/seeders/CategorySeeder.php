<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 親カテゴリー
        $parentCategories = [
            [
                'name' => '家電製品',
                'slug' => 'electronics',
                'description' => '最新の家電製品を幅広く取り揃えています。',
                'sort_order' => 1,
                'is_visible' => true,
            ],
            [
                'name' => 'ファッション',
                'slug' => 'fashion',
                'description' => '季節に合わせた最新のトレンドアイテムを提供します。',
                'sort_order' => 2,
                'is_visible' => true,
            ],
            [
                'name' => '食品・飲料',
                'slug' => 'food-beverage',
                'description' => '厳選された食品と飲料を取り扱っています。',
                'sort_order' => 3,
                'is_visible' => true,
            ],
            [
                'name' => '書籍・メディア',
                'slug' => 'books-media',
                'description' => '書籍、音楽、映画などの各種メディアコンテンツを取り揃えています。',
                'sort_order' => 4,
                'is_visible' => true,
            ],
        ];

        // 子カテゴリー
        $childCategories = [
            // 家電製品の子カテゴリー
            [
                'name' => 'スマートフォン',
                'slug' => 'smartphones',
                'description' => '最新のスマートフォンを取り揃えています。',
                'sort_order' => 1,
                'is_visible' => true,
                'parent_category' => 'electronics',
            ],
            [
                'name' => 'テレビ',
                'slug' => 'televisions',
                'description' => '高画質・高音質なテレビを提供しています。',
                'sort_order' => 2,
                'is_visible' => true,
                'parent_category' => 'electronics',
            ],
            [
                'name' => 'オーディオ機器',
                'slug' => 'audio-equipment',
                'description' => '音響機器の幅広いラインナップ。',
                'sort_order' => 3,
                'is_visible' => true,
                'parent_category' => 'electronics',
            ],
            
            // ファッションの子カテゴリー
            [
                'name' => 'メンズウェア',
                'slug' => 'mens-wear',
                'description' => '男性向けの最新ファッションアイテム。',
                'sort_order' => 1,
                'is_visible' => true,
                'parent_category' => 'fashion',
            ],
            [
                'name' => 'レディースウェア',
                'slug' => 'womens-wear',
                'description' => '女性向けの最新ファッションアイテム。',
                'sort_order' => 2,
                'is_visible' => true,
                'parent_category' => 'fashion',
            ],
            [
                'name' => 'アクセサリー',
                'slug' => 'accessories',
                'description' => 'ファッションを彩る各種アクセサリー。',
                'sort_order' => 3,
                'is_visible' => true,
                'parent_category' => 'fashion',
            ],
            
            // 食品・飲料の子カテゴリー
            [
                'name' => '飲料',
                'slug' => 'beverages',
                'description' => '様々な飲み物を取り揃えています。',
                'sort_order' => 1,
                'is_visible' => true,
                'parent_category' => 'food-beverage',
            ],
            [
                'name' => 'スナック菓子',
                'slug' => 'snacks',
                'description' => '人気のスナック菓子を多数ご用意。',
                'sort_order' => 2,
                'is_visible' => true,
                'parent_category' => 'food-beverage',
            ],
            
            // 書籍・メディアの子カテゴリー
            [
                'name' => '小説',
                'slug' => 'novels',
                'description' => '人気作家の作品や話題の小説を取り揃えています。',
                'sort_order' => 1,
                'is_visible' => true,
                'parent_category' => 'books-media',
            ],
            [
                'name' => '音楽CD',
                'slug' => 'music-cds',
                'description' => '様々なジャンルの音楽CDを取り扱っています。',
                'sort_order' => 2,
                'is_visible' => true,
                'parent_category' => 'books-media',
            ],
            [
                'name' => '映画DVD/BD',
                'slug' => 'movie-dvd-bd',
                'description' => '映画やドラマのDVD/ブルーレイディスクを取り揃えています。',
                'sort_order' => 3,
                'is_visible' => true,
                'parent_category' => 'books-media',
            ],
        ];

        // 親カテゴリーの登録
        foreach ($parentCategories as $category) {
            Category::create($category);
        }

        // 子カテゴリーの登録
        foreach ($childCategories as $childCategory) {
            // 親カテゴリーのIDを取得
            $parentCategorySlug = $childCategory['parent_category'];
            $parentCategory = Category::where('slug', $parentCategorySlug)->first();
            
            if ($parentCategory) {
                // 親カテゴリーのキーを削除してparent_idを設定
                unset($childCategory['parent_category']);
                $childCategory['parent_id'] = $parentCategory->id;
                
                Category::create($childCategory);
            }
        }
    }
}
