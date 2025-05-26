<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * 商品一覧を表示
     */
    public function index(Request $request)
    {
        $query = Product::query()->with('category');
        
        // 検索フィルタリング
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }
        
        // カテゴリーフィルタリング
        if ($request->has('category_id') && $request->input('category_id') != '') {
            $query->where('category_id', $request->input('category_id'));
        }
        
        // 在庫状態フィルタリング
        if ($request->has('stock_status')) {
            $stockStatus = $request->input('stock_status');
            if ($stockStatus === 'in_stock') {
                $query->where('stock', '>', 0);
            } elseif ($stockStatus === 'out_of_stock') {
                $query->where('stock', 0);
            } elseif ($stockStatus === 'low_stock') {
                $query->where('stock', '>', 0)->where('stock', '<=', 5);
            }
        }
        
        // 表示状態フィルタリング
        if ($request->has('visibility')) {
            $visibility = $request->input('visibility');
            if ($visibility === 'visible') {
                $query->where('is_visible', true);
            } elseif ($visibility === 'hidden') {
                $query->where('is_visible', false);
            }
        }
        
        // 並び替え
        $sortField = $request->input('sort', 'created_at');
        $sortDirection = $request->input('direction', 'desc');
        $query->orderBy($sortField, $sortDirection);
        
        $products = $query->paginate(15);
        $categories = Category::all();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * 商品作成フォームを表示
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * 商品を保存
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'main_image' => 'required|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
        ]);
        
        // スラグの作成
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        
        // スラグが重複する場合は連番を追加
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        
        // 商品データの作成
        $product = Product::create([
            'name' => $request->name,
            'slug' => $slug,
            'description' => $request->description,
            'features' => $request->features,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'is_visible' => $request->has('is_visible'),
            'is_featured' => $request->has('is_featured'),
            'category_id' => $request->category_id,
        ]);
        
        // メイン画像の保存
        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_main' => true,
            ]);
        }
        
        // 追加画像の保存
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => false,
                ]);
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', '商品を作成しました。');
    }

    /**
     * 商品詳細を表示
     */
    public function show(Product $product)
    {
        $product->load(['category', 'images']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * 商品編集フォームを表示
     */
    public function edit(Product $product)
    {
        $product->load(['category', 'images']);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * 商品を更新
     */
    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'main_image' => 'nullable|image|max:2048',
            'additional_images.*' => 'nullable|image|max:2048',
        ]);
        
        // 商品データの更新
        $product->update([
            'name' => $request->name,
            'description' => $request->description,
            'features' => $request->features,
            'specifications' => $request->specifications,
            'price' => $request->price,
            'sale_price' => $request->sale_price,
            'stock' => $request->stock,
            'sku' => $request->sku,
            'is_visible' => $request->has('is_visible'),
            'is_featured' => $request->has('is_featured'),
            'category_id' => $request->category_id,
        ]);
        
        // メイン画像の更新
        if ($request->hasFile('main_image')) {
            // 既存のメイン画像を非メインに変更
            $product->images()->where('is_main', true)->update(['is_main' => false]);
            
            // 新しいメイン画像を保存
            $path = $request->file('main_image')->store('products', 'public');
            
            ProductImage::create([
                'product_id' => $product->id,
                'path' => $path,
                'is_main' => true,
            ]);
        }
        
        // 追加画像の保存
        if ($request->hasFile('additional_images')) {
            foreach ($request->file('additional_images') as $image) {
                $path = $image->store('products', 'public');
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'path' => $path,
                    'is_main' => false,
                ]);
            }
        }
        
        // 削除する画像の処理
        if ($request->has('delete_images')) {
            $deleteImages = $request->input('delete_images');
            $productImages = ProductImage::whereIn('id', $deleteImages)->get();
            
            foreach ($productImages as $image) {
                // メイン画像は削除しない（少なくとも1つは必要）
                if ($image->is_main && $product->images()->where('is_main', true)->count() <= 1) {
                    continue;
                }
                
                // ストレージから画像を削除
                if (Storage::disk('public')->exists($image->path)) {
                    Storage::disk('public')->delete($image->path);
                }
                
                // データベースから削除
                $image->delete();
            }
        }
        
        return redirect()->route('admin.products.index')
            ->with('success', '商品を更新しました。');
    }

    /**
     * 商品を削除
     */
    public function destroy(Product $product)
    {
        // 商品に関連する画像を削除
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->path)) {
                Storage::disk('public')->delete($image->path);
            }
        }
        
        // 商品を削除
        $product->delete();
        
        return redirect()->route('admin.products.index')
            ->with('success', '商品を削除しました。');
    }
}
