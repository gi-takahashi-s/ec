<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'image_path',
        'alt_text',
        'sort_order',
        'is_main',
    ];

    /**
     * 画像が属する商品を取得
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
