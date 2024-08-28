<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CryptoWallet extends Model
{
    use HasFactory,
        SoftDeletes;

    protected $fillable = [ 'title', 'address', 'type', 'amount', 'user_id' ];

    protected $hidden = [
        'user_id',
        'created_at',
        'updated_at',
        'deleted_at'
    ];
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($wallet) {
            $existingWalletsCount = $wallet->user->wallets()
                ->where('type', $wallet->type)
                ->count();

            if ($existingWalletsCount >= 1) {
                return false;
            }

        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
