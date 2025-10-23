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
        $query = Product::query()->with(['category', 'mainImage']);
        
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
            $categoryId = $request->input('category_id');
            $category = Category::find($categoryId);
            
            if ($category) {
                // 選択されたカテゴリーが親カテゴリーの場合、子カテゴリーも含める
                $categoryIds = [$categoryId];
                if ($category->children()->exists()) {
                    $categoryIds = array_merge($categoryIds, $category->children()->pluck('id')->toArray());
                }
                $query->whereIn('category_id', $categoryIds);
            } else {
                $query->where('category_id', $categoryId);
            }
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
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
        
        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * 商品作成フォームを表示
     */
    public function create()
    {
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
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
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku',
            'category_id' => 'required|exists:categories,id',
            'main_image_data' => 'required|string',
            'additional_images_data' => 'nullable|string',
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
        if ($request->filled('main_image_data')) {
            $path = $this->saveBase64Image($request->main_image_data, 'products');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_main' => true,
            ]);
        }
        
        // 追加画像の保存
        if ($request->filled('additional_images_data')) {
            $additionalImages = json_decode($request->additional_images_data, true);
            
            if (is_array($additionalImages)) {
                foreach ($additionalImages as $imageData) {
                    $path = $this->saveBase64Image($imageData, 'products');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => false,
                    ]);
                }
            }
        }
        
        // 検索・フィルターパラメータを保持してリダイレクト
        $queryParams = $request->only(['search', 'category_id', 'stock_status', 'visibility', 'sort', 'direction', 'page']);
        
        return redirect()->route('admin.products.index', $queryParams)
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
        $categories = Category::with('children')->whereNull('parent_id')->orderBy('sort_order')->get();
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
            'price' => 'required|integer|min:0',
            'sale_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|unique:products,sku,' . $product->id,
            'category_id' => 'required|exists:categories,id',
            'main_image_data' => 'nullable|string',
            'additional_images_data' => 'nullable|string',
            'existing_images_order' => 'nullable|string',
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
        
        // 既存画像の削除処理
        $existingImages = $product->images;
        foreach ($existingImages as $image) {
            $fieldName = 'existing_image_' . $image->id;
            if ($request->input($fieldName) === 'delete') {
                // ストレージから画像を削除
                if (Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
                // データベースから削除
                $image->delete();
            }
        }
        
        // 既存画像の順序更新
        if ($request->filled('existing_images_order')) {
            $existingOrder = json_decode($request->existing_images_order, true);
            
            if (is_array($existingOrder)) {
                // 全ての既存画像をいったん非メインに設定
                $product->images()->update(['is_main' => false]);
                
                foreach ($existingOrder as $orderData) {
                    $product->images()
                        ->where('id', $orderData['id'])
                        ->update([
                            'is_main' => $orderData['isMain'] ?? false
                        ]);
                }
            }
        }
        
        // 新しいメイン画像の保存
        if ($request->filled('main_image_data')) {
            // 既存のメイン画像を非メインに変更
            $product->images()->update(['is_main' => false]);
            
            // 新しいメイン画像を保存
            $path = $this->saveBase64Image($request->main_image_data, 'products');
            
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => $path,
                'is_main' => true,
            ]);
        }
        
        // 新しい追加画像の保存
        if ($request->filled('additional_images_data')) {
            $additionalImages = json_decode($request->additional_images_data, true);
            
            if (is_array($additionalImages)) {
                foreach ($additionalImages as $imageData) {
                    $path = $this->saveBase64Image($imageData, 'products');
                    
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'is_main' => false,
                    ]);
                }
            }
        }
        
        // 検索・フィルターパラメータを保持してリダイレクト
        $queryParams = $request->only(['search', 'category_id', 'stock_status', 'visibility', 'sort', 'direction', 'page']);
        
        return redirect()->route('admin.products.index', $queryParams)
            ->with('success', '商品を更新しました。');
    }

    /**
     * 商品を削除
     */
    public function destroy(Request $request, Product $product)
    {
        // 商品に関連する画像を削除
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image_path)) {
                Storage::disk('public')->delete($image->image_path);
            }
        }
        
        // 商品を削除
        $product->delete();
        
        // 検索・フィルターパラメータを保持してリダイレクト
        $queryParams = $request->only(['search', 'category_id', 'stock_status', 'visibility', 'sort', 'direction', 'page']);
        
        return redirect()->route('admin.products.index', $queryParams)
            ->with('success', '商品を削除しました。');
    }

    /**
     * Base64画像データをファイルとして保存
     */
    private function saveBase64Image($base64Data, $directory)
    {
        // data:image/jpeg;base64, の部分を除去
        $imageData = preg_replace('/^data:image\/\w+;base64,/', '', $base64Data);
        $imageData = base64_decode($imageData);
        
        // ファイル名を生成
        $fileName = uniqid() . '.jpg';
        $filePath = $directory . '/' . $fileName;
        
        // ストレージに保存
        Storage::disk('public')->put($filePath, $imageData);
        
        return $filePath;
    }
}
