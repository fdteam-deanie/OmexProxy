<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrialPeriod extends Model
{
    protected $fillable = [
        'user_id',
        'payment_id',
        'active',
        'expired_at',
    ];

    protected $casts = [
        'active' => 'boolean',
        'expired_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function payment(): BelongsTo
    {
        return $this->belongsTo(Payment::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }
}
