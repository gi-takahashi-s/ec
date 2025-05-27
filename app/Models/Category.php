<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'sort_order',
        'is_visible',
        'image_path',
        'parent_id',
    ];

    /**
     * 子カテゴリーを取得
     */
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * 親カテゴリーを取得
     */
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    /**
     * カテゴリーに属する商品を取得
     */
    public function products()
    {
        return $this->hasMany(Product::class);
    }

    /**
     * カテゴリーとその子カテゴリーに属する全ての商品を取得
     */
    public function allProducts()
    {
        $categoryIds = $this->children()->pluck('id')->push($this->id);
        return Product::whereIn('category_id', $categoryIds);
    }
}
