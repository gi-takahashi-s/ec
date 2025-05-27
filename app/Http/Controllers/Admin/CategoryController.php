<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * カテゴリー一覧表示
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // 親カテゴリー（parent_idがnull）のみを取得し、子カテゴリーをロードする
        $categories = Category::with(['children' => function($query) {
                            $query->orderBy('sort_order')->orderBy('name');
                        }])
                        ->whereNull('parent_id')
                        ->orderBy('sort_order')
                        ->orderBy('name')
                        ->get();
        
        return view('admin.categories.index', compact('categories'));
    }

    /**
     * カテゴリー作成フォーム表示
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::where('parent_id', null)
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
        
        return view('admin.categories.create', compact('categories'));
    }

    /**
     * カテゴリーの保存処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:categories',
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
            'is_visible' => 'boolean',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoryData = $request->except('image');
        
        // 画像アップロード処理
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('categories', 'public');
            $categoryData['image_path'] = $path;
        }
        
        // チェックボックスの処理
        $categoryData['is_visible'] = $request->has('is_visible');
        
        Category::create($categoryData);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'カテゴリーを作成しました。');
    }

    /**
     * カテゴリー詳細表示
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show(Category $category)
    {
        $category->load(['parent', 'children', 'products']);
        
        return view('admin.categories.show', compact('category'));
    }

    /**
     * カテゴリー編集フォーム表示
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $categories = Category::where('id', '!=', $category->id)
                    ->where(function($query) use ($category) {
                        $query->where('parent_id', null)
                              ->orWhere('parent_id', '!=', $category->id);
                    })
                    ->orderBy('sort_order')
                    ->orderBy('name')
                    ->get();
        
        return view('admin.categories.edit', compact('category', 'categories'));
    }

    /**
     * カテゴリー更新処理
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories')->ignore($category->id),
            ],
            'description' => 'nullable|string',
            'sort_order' => 'required|integer',
            'is_visible' => 'boolean',
            'parent_id' => [
                'nullable',
                'exists:categories,id',
                function ($attribute, $value, $fail) use ($category) {
                    // 自分自身を親カテゴリーに設定することはできない
                    if ($value == $category->id) {
                        $fail('自分自身を親カテゴリーに設定することはできません。');
                    }
                    
                    // 自分の子カテゴリーを親カテゴリーに設定することはできない
                    if ($value) {
                        $childIds = Category::where('parent_id', $category->id)->pluck('id')->toArray();
                        if (in_array($value, $childIds)) {
                            $fail('子カテゴリーを親カテゴリーに設定することはできません。');
                        }
                    }
                },
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $categoryData = $request->except(['image', 'is_visible']);
        
        // 画像アップロード処理
        if ($request->hasFile('image')) {
            // 古い画像を削除
            if ($category->image_path) {
                Storage::disk('public')->delete($category->image_path);
            }
            
            $path = $request->file('image')->store('categories', 'public');
            $categoryData['image_path'] = $path;
        }
        
        // チェックボックスの処理
        $categoryData['is_visible'] = $request->has('is_visible');
        
        $category->update($categoryData);
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'カテゴリーを更新しました。');
    }

    /**
     * カテゴリー削除処理
     *
     * @param  \App\Models\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        // 子カテゴリーがある場合は削除できない
        if ($category->children()->count() > 0) {
            return redirect()->route('admin.categories.index')
                            ->with('error', '子カテゴリーが存在するため削除できません。先に子カテゴリーを削除してください。');
        }
        
        // カテゴリーに紐づく商品の処理
        if ($category->products()->count() > 0) {
            // カテゴリーを削除する前に、紐づく商品のカテゴリーをnullに設定
            $category->products()->update(['category_id' => null]);
        }
        
        // 画像の削除
        if ($category->image_path) {
            Storage::disk('public')->delete($category->image_path);
        }
        
        $category->delete();
        
        return redirect()->route('admin.categories.index')
                        ->with('success', 'カテゴリーを削除しました。');
    }
} 