<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'features',
        'specifications',
        'price',
        'sale_price',
        'stock',
        'sku',
        'is_active',
        'is_featured',
        'image',
        'category_id',
    ];

    /**
     * 商品のカテゴリーを取得
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * 商品の画像を取得
     */
    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    /**
     * 商品のメイン画像を取得
     */
    public function mainImage()
    {
        return $this->hasOne(ProductImage::class)->where('is_main', true);
    }

    /**
     * 在庫があるかどうかを確認
     */
    public function isInStock()
    {
        return $this->stock > 0;
    }

    /**
     * セール中かどうかを確認
     */
    public function isOnSale()
    {
        return $this->sale_price !== null && $this->sale_price < $this->price;
    }

    /**
     * 現在の販売価格を取得（セール価格または通常価格）
     */
    public function getCurrentPrice()
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }
}
