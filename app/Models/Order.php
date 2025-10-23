<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'shipping_address_id',
        'shipping_company_id',
        'selected_delivery_time',
        'order_number',
        'subtotal',
        'tax',
        'shipping_fee',
        'total',
        'payment_method',
        'payment_status',
        'stripe_payment_id',
        'stripe_session_url',
        'order_status',
        'notes',
        'paid_at',
        'shipped_at',
        'delivered_at',
        'transfer_deadline',
        'transfer_confirmed_at',
        'tracking_number',
        'shipping_method',
        'delivery_date',
        'delivery_time',
        'shipping_memo',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subtotal' => 'float',
        'tax' => 'float',
        'shipping_fee' => 'float',
        'total' => 'float',
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'transfer_deadline' => 'datetime',
        'transfer_confirmed_at' => 'datetime',
        'delivery_date' => 'date',
    ];

    // 決済方法定数
    const PAYMENT_METHOD_STRIPE = 'stripe';
    const PAYMENT_METHOD_BANK_TRANSFER = 'bank_transfer';

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the shipping address for the order.
     */
    public function shippingAddress(): BelongsTo
    {
        return $this->belongsTo(ShippingAddress::class);
    }

    /**
     * Get the shipping company for the order.
     */
    public function shippingCompany(): BelongsTo
    {
        return $this->belongsTo(ShippingCompany::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the bank transfer for the order.
     */
    public function bankTransfer(): HasOne
    {
        return $this->hasOne(BankTransfer::class);
    }

    /**
     * Generate a unique order number.
     */
    public static function generateOrderNumber(): string
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        
        return $prefix . $timestamp . $random;
    }

    /**
     * 銀行振込かどうかを判定
     */
    public function isBankTransfer(): bool
    {
        return $this->payment_method === self::PAYMENT_METHOD_BANK_TRANSFER;
    }

    /**
     * Stripe決済かどうかを判定
     */
    public function isStripePayment(): bool
    {
        return $this->payment_method === self::PAYMENT_METHOD_STRIPE;
    }

    /**
     * 振込期限切れかどうかを判定
     */
    public function isTransferExpired(): bool
    {
        return $this->isBankTransfer() && 
               $this->transfer_deadline && 
               Carbon::now()->isAfter($this->transfer_deadline);
    }

    /**
     * 振込確認済みかどうかを判定
     */
    public function isTransferConfirmed(): bool
    {
        return $this->isBankTransfer() && 
               $this->transfer_confirmed_at !== null;
    }

    /**
     * 銀行振込待ちの注文を取得
     */
    public static function scopeBankTransferPending($query)
    {
        return $query->where('payment_method', self::PAYMENT_METHOD_BANK_TRANSFER)
                    ->where('payment_status', 'pending');
    }

    /**
     * 振込期限切れの注文を取得
     */
    public static function scopeTransferExpired($query)
    {
        return $query->where('payment_method', self::PAYMENT_METHOD_BANK_TRANSFER)
                    ->where('payment_status', 'pending')
                    ->where('transfer_deadline', '<', Carbon::now());
    }

    /**
     * 振込確認済みの注文を取得
     */
    public static function scopeTransferConfirmed($query)
    {
        return $query->where('payment_method', self::PAYMENT_METHOD_BANK_TRANSFER)
                    ->whereNotNull('transfer_confirmed_at');
    }
}
