<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'description',
        'is_enabled',
        'sort_order',
        'settings',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'settings' => 'array',
    ];

    // 決済方法の定数
    const STRIPE = 'stripe';
    const BANK_TRANSFER = 'bank_transfer';
    const CASH_ON_DELIVERY = 'cash_on_delivery';

    /**
     * デフォルトの決済方法を取得
     */
    public static function getDefaults()
    {
        return [
            [
                'key' => self::STRIPE,
                'name' => 'クレジットカード決済（Stripe）',
                'description' => 'Stripeを使用したクレジットカード決済',
                'is_enabled' => false,
                'sort_order' => 1,
                'settings' => [],
            ],
            [
                'key' => self::BANK_TRANSFER,
                'name' => '銀行振込',
                'description' => '銀行振込による決済',
                'is_enabled' => false,
                'sort_order' => 2,
                'settings' => [],
            ],
            [
                'key' => self::CASH_ON_DELIVERY,
                'name' => '代金引換',
                'description' => '商品受け取り時の代金引換決済',
                'is_enabled' => false,
                'sort_order' => 3,
                'settings' => [],
            ],
        ];
    }

    /**
     * デフォルトの決済方法を作成
     */
    public static function createDefaults()
    {
        $defaults = self::getDefaults();
        
        foreach ($defaults as $default) {
            // 既存のレコードがない場合のみ作成
            if (!self::where('key', $default['key'])->exists()) {
                self::create($default);
            }
        }
    }

    /**
     * キーで決済方法を取得
     */
    public static function getByKey($key)
    {
        return self::where('key', $key)->first();
    }

    /**
     * 有効な決済方法を取得
     */
    public static function getEnabled()
    {
        return self::where('is_enabled', true)
                   ->orderBy('sort_order')
                   ->get();
    }

    /**
     * 全ての決済方法を表示順で取得
     */
    public static function getAllOrdered()
    {
        return self::orderBy('sort_order')->get();
    }

    /**
     * 代引手数料を計算
     */
    public function calculateCodFee($amount)
    {
        if ($this->key !== self::CASH_ON_DELIVERY) {
            return 0;
        }

        $ranges = $this->settings['cod_fee_ranges'] ?? [];
        
        if (empty($ranges)) {
            // レンジが設定されていない場合は固定手数料を使用
            return $this->settings['cod_fee'] ?? 330;
        }

        // 金額に適用されるレンジを検索
        foreach ($ranges as $range) {
            $minAmount = (int)($range['min_amount'] ?? 0);
            $maxAmount = isset($range['max_amount']) && $range['max_amount'] !== '' ? (int)$range['max_amount'] : null;
            
            if ($amount >= $minAmount && ($maxAmount === null || $amount <= $maxAmount)) {
                return (int)($range['fee'] ?? 0);
            }
        }

        // 該当するレンジがない場合は0を返す
        return 0;
    }

    /**
     * 代引手数料レンジの表示用文字列を取得
     */
    public function getCodFeeRangesDisplay()
    {
        if ($this->key !== self::CASH_ON_DELIVERY) {
            return '';
        }

        $ranges = $this->settings['cod_fee_ranges'] ?? [];
        
        if (empty($ranges)) {
            $fee = $this->settings['cod_fee'] ?? 330;
            return "一律 {$fee}円";
        }

        $display = [];
        foreach ($ranges as $range) {
            $minAmount = number_format($range['min_amount'] ?? 0);
            $maxAmount = isset($range['max_amount']) && $range['max_amount'] !== '' ? number_format($range['max_amount']) : null;
            $fee = number_format($range['fee'] ?? 0);
            
            if ($maxAmount !== null) {
                $display[] = "{$minAmount}円～{$maxAmount}円: {$fee}円";
            } else {
                $display[] = "{$minAmount}円以上: {$fee}円";
            }
        }

        return implode(', ', $display);
    }
}
