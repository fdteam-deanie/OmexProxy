<?php

namespace App\Models\Pivot;

use App\Models\Cart;
use App\Models\Proxy;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\Pivot;

class CartProxy extends Pivot
{

	protected $fillable = ['cart_id', 'proxy_id', 'count'];

	public function cart(): BelongsTo
    {
        return $this->belongsTo(Cart::class);
    }

    public function proxy(): BelongsTo
    {
        return $this->belongsTo(Proxy::class);
    }

}
