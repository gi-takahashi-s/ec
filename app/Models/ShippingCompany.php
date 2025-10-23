<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCompany extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'method_name',
        'payment_methods',
        'delivery_times',
        'uniform_shipping_fee',
        'uniform_fee',
        'prefecture_fees',
        'notes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'payment_methods' => 'array',
        'delivery_times' => 'array',
        'prefecture_fees' => 'array',
        'uniform_shipping_fee' => 'boolean',
        'is_active' => 'boolean',
        'uniform_fee' => 'integer',
    ];

    /**
     * 有効な配送業者のみを取得
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * 表示順でソート
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('id');
    }

    /**
     * 都道府県リストを取得
     */
    public static function getPrefectures()
    {
        return [
            '北海道', '青森県', '岩手県', '宮城県', '秋田県', '山形県', '福島県',
            '茨城県', '栃木県', '群馬県', '埼玉県', '千葉県', '東京都', '神奈川県',
            '新潟県', '富山県', '石川県', '福井県', '山梨県', '長野県',
            '岐阜県', '静岡県', '愛知県', '三重県',
            '滋賀県', '京都府', '大阪府', '兵庫県', '奈良県', '和歌山県',
            '鳥取県', '島根県', '岡山県', '広島県', '山口県',
            '徳島県', '香川県', '愛媛県', '高知県',
            '福岡県', '佐賀県', '長崎県', '熊本県', '大分県', '宮崎県', '鹿児島県', '沖縄県'
        ];
    }

    /**
     * 支払方法の選択肢を取得
     */
    public static function getPaymentMethods()
    {
        return [
            'credit_card' => 'クレジットカード',
            'bank_transfer' => '銀行振込',
            'cash_on_delivery' => '代金引換',
            'convenience_store' => 'コンビニ決済',
        ];
    }
}
