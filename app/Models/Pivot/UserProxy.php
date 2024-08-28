<?php

namespace App\Models\Pivot;

use App\Models\Order;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserProxy extends Pivot
{
    protected $table = 'user_proxies';

	protected $fillable = ['user_id', 'order_id', 'proxy_id', 'unlimited_subscription_id', 'is_paid', 'paid_at', 'expired_at', 'connect_host', 'username', 'password'];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function proxy(): BelongsTo
    {
        return $this->belongsTo(Proxy::class, 'proxy_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'order_id');
    }



    public function getConnectionInfo(): string
    {
        return "{$this->username}:{$this->password}:{$this->connect_host}";
    }
}
