<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * カテゴリー一覧を表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('is_visible', true)
                             ->whereNull('parent_id')
                             ->with('children')
                             ->orderBy('sort_order')
                             ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * 特定のカテゴリーとその商品を表示
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $category = Category::where('slug', $slug)
                           ->where('is_visible', true)
                           ->with(['products' => function($query) {
                               $query->where('is_active', true)
                                     ->orderBy('created_at', 'desc');
                           }])
                           ->firstOrFail();

        $products = $category->products()->paginate(12);

        return view('categories.show', compact('category', 'products'));
    }
}
