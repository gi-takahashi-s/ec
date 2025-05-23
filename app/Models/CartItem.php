<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
        'price',
    ];
    
    /**
     * このアイテムが属するカートを取得
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }
    
    /**
     * このアイテムの商品情報を取得
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * アイテムの小計を取得
     */
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }
}
