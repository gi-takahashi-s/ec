<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class BankTransfer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'bank_name',
        'branch_name',
        'account_type',
        'account_number',
        'account_holder',
        'transfer_amount',
        'transfer_deadline',
        'transfer_confirmed_at',
        'transfer_status',
        'admin_notes',
        'confirmed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'transfer_amount' => 'float',
        'transfer_deadline' => 'datetime',
        'transfer_confirmed_at' => 'datetime',
    ];

    // ステータス定数
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_EXPIRED = 'expired';

    /**
     * Get the order that owns the bank transfer.
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the user who confirmed the transfer.
     */
    public function confirmedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    /**
     * 振込期限を自動計算して設定
     */
    public function setTransferDeadline(int $days = 7): void
    {
        $this->transfer_deadline = Carbon::now()->addDays($days);
    }

    /**
     * 振込期限切れかどうかを判定
     */
    public function isExpired(): bool
    {
        return $this->transfer_deadline && Carbon::now()->isAfter($this->transfer_deadline);
    }

    /**
     * 振込確認処理
     */
    public function confirm(User $confirmedBy, string $notes = null): bool
    {
        if ($this->transfer_status === self::STATUS_CONFIRMED) {
            return false; // 既に確認済み
        }

        $this->transfer_confirmed_at = Carbon::now();
        $this->transfer_status = self::STATUS_CONFIRMED;
        $this->confirmed_by = $confirmedBy->id;
        
        if ($notes) {
            $this->admin_notes = $notes;
        }

        return $this->save();
    }

    /**
     * 期限切れステータスに更新
     */
    public function markAsExpired(): bool
    {
        if ($this->transfer_status === self::STATUS_PENDING && $this->isExpired()) {
            $this->transfer_status = self::STATUS_EXPIRED;
            return $this->save();
        }
        
        return false;
    }

    /**
     * 振込待ちのレコードを取得
     */
    public static function scopePending($query)
    {
        return $query->where('transfer_status', self::STATUS_PENDING);
    }

    /**
     * 期限切れのレコードを取得
     */
    public static function scopeExpired($query)
    {
        return $query->where('transfer_status', self::STATUS_PENDING)
                    ->where('transfer_deadline', '<', Carbon::now());
    }

    /**
     * 確認済みのレコードを取得
     */
    public static function scopeConfirmed($query)
    {
        return $query->where('transfer_status', self::STATUS_CONFIRMED);
    }
}
