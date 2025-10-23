<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email',
        'ip_address',
        'user_agent',
        'status',
        'failure_reason',
        'logged_in_at',
        'logged_out_at',
    ];

    protected $casts = [
        'logged_in_at' => 'datetime',
        'logged_out_at' => 'datetime',
    ];

    /**
     * ユーザーとのリレーション
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 成功したログインのスコープ
     */
    public function scopeSuccessful($query)
    {
        return $query->where('status', 'success');
    }

    /**
     * 失敗したログインのスコープ
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    /**
     * 最新順のスコープ
     */
    public function scopeLatest($query)
    {
        return $query->orderBy('logged_in_at', 'desc');
    }

    /**
     * ログイン履歴を記録
     */
    public static function recordLogin($user, $request, $status = 'success', $failureReason = null)
    {
        return static::create([
            'user_id' => $user ? $user->id : null,
            'email' => $user ? $user->email : $request->input('email'),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'status' => $status,
            'failure_reason' => $failureReason,
            'logged_in_at' => now(),
        ]);
    }

    /**
     * ログアウト時刻を記録
     */
    public function recordLogout()
    {
        $this->update(['logged_out_at' => now()]);
    }
}
