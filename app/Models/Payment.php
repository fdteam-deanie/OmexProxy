<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Payment extends Model
{
    public const CACHE_BALANCE_KEY = 'user_balance_';
    public const CACHE_BONUS_BALANCE_KEY = 'user_bonus_balance_';

    const PENDING = 0;
    const SUCCESS = 1;
    const FAILED = 2;

	protected $fillable = ['user_id', 'order_id', 'is_deposit', 'is_bonus', 'amount', 'status'];

    protected static function boot()
    {
        parent::boot();
        static::created(function (Payment $payment) {
            Cache::forget(self::CACHE_BALANCE_KEY.$payment->user_id);
            Cache::forget(self::CACHE_BONUS_BALANCE_KEY.$payment->user_id);
        });
    }


    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeOnlyDeposits($query): Builder
    {
        return $query->where('is_deposit', true);
    }

    public function scopeOnlySpendings($query): Builder
    {
        return $query->where('is_deposit', false);
    }

    public function scopeBonus($query): Builder
    {
        return $query->where('is_bonus', true);
    }

    public function scopeNotBonus($query): Builder
    {
        return $query->where('is_bonus', false);
    }

}
