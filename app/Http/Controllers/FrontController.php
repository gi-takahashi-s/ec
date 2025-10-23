<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\ShopSettingService;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    /**
     * トップページを表示
     */
    public function index()
    {
        // おすすめ商品の取得
        $featuredProducts = Product::where('is_featured', true)
                                  ->where('is_visible', true)
                                  ->with(['mainImage'])
                                  ->take(8)
                                  ->get();
        
        // 新着商品の取得
        $newProducts = Product::where('is_visible', true)
                             ->with(['mainImage'])
                             ->orderBy('created_at', 'desc')
                             ->take(8)
                             ->get();
        
        // 親カテゴリーの取得
        $categories = Category::where('is_visible', true)
                             ->whereNull('parent_id')
                             ->orderBy('sort_order')
                             ->get();
        
        return view('welcome', compact('featuredProducts', 'newProducts', 'categories'));
    }

    /**
     * サブページを表示
     */
    public function subPage()
    {
        return view('front.sub');
    }

    /**
     * 特定商取引法表記ページを表示
     */
    public function legal()
    {
        $legalInfo = ShopSettingService::getLegalInfo();
        
        return view('front.legal', compact('legalInfo'));
    }

    /**
     * プライバシーポリシーページを表示
     */
    public function privacy()
    {
        $privacyInfo = ShopSettingService::getPrivacyPolicy();
        
        return view('front.privacy', compact('privacyInfo'));
    }
} 