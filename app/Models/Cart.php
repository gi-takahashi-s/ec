<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'session_id',
    ];
    
    /**
     * カートに紐づく商品アイテムを取得
     */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }
    
    /**
     * カートの所有ユーザーを取得
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * カート内の合計金額を計算
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->price * $item->quantity;
        });
    }
    
    /**
     * カート内の合計商品数を計算
     */
    public function getTotalQuantityAttribute()
    {
        return $this->items->sum('quantity');
    }
}
