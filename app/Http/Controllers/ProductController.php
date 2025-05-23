<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * 商品一覧を表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Product::where('is_active', true);

        // カテゴリーでフィルタリング
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // 検索キーワードでフィルタリング
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%");
            });
        }

        // 並び替え
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'newest':
                    $query->orderBy('created_at', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $products = $query->paginate(12);
        $categories = Category::where('is_visible', true)->orderBy('sort_order')->get();

        return view('products.index', compact('products', 'categories'));
    }

    /**
     * 商品詳細を表示
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = Product::where('slug', $slug)
                          ->where('is_active', true)
                          ->with(['category', 'images'])
                          ->firstOrFail();

        $relatedProducts = Product::where('category_id', $product->category_id)
                                 ->where('id', '!=', $product->id)
                                 ->where('is_active', true)
                                 ->take(4)
                                 ->get();

        return view('products.show', compact('product', 'relatedProducts'));
    }

    /**
     * カテゴリー別の商品一覧を表示
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function category($slug)
    {
        $category = Category::where('slug', $slug)
                           ->where('is_visible', true)
                           ->firstOrFail();

        $products = Product::where('category_id', $category->id)
                          ->where('is_active', true)
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        $categories = Category::where('is_visible', true)->orderBy('sort_order')->get();

        return view('products.category', compact('category', 'products', 'categories'));
    }

    /**
     * おすすめ商品一覧を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function featured()
    {
        $featuredProducts = Product::where('is_featured', true)
                                  ->where('is_active', true)
                                  ->orderBy('created_at', 'desc')
                                  ->paginate(12);

        return view('products.featured', compact('featuredProducts'));
    }

    /**
     * 商品検索結果を表示
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $searchTerm = $request->input('query');
        
        $products = Product::where('is_active', true)
                          ->where(function($query) use ($searchTerm) {
                              $query->where('name', 'like', "%{$searchTerm}%")
                                   ->orWhere('description', 'like', "%{$searchTerm}%");
                          })
                          ->orderBy('created_at', 'desc')
                          ->paginate(12);

        return view('products.search', compact('products', 'searchTerm'));
    }
}
