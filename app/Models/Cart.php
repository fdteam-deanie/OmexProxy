<?php

namespace App\Models;

use App\Models\Pivot\CartProxy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Hash;

class Cart extends Model {

    use HasFactory;

	protected $table = 'cart';
	protected $fillable = ['user_id', 'hash', 'status'];

	protected static ?Cart $cart = null;

    protected static function booted() {
        static::deleting(function(Cart $cart) {
            $cart->proxies()->detach();
        });
    }

	protected static function initCart($userId): ?Cart
    {
        static::$cart = static::getCartByUserId($userId);
        if(empty(static::$cart)) {
            static::$cart = static::newCart($userId);
        }
        return static::$cart;
	}

	public static function getCart(int $userId): Cart
    {
        if(empty(static::$cart)) {
            static::$cart = static::initCart($userId);
        }

        return static::$cart;
	}

    public function getTotalAttribute(): float
    {
        $total = $this->proxies->map(function(Proxy $proxy) {
            return $proxy->price * $proxy->pivot->count;
        })->sum();
        return number_format($total, 2, '.', '');
    }

	public static function getCartByUserId($userId): ?Cart
    {
		return static::where(['user_id' => $userId, 'status' => 1])->first();
	}

	public static function clean(): void
    {
		static::$cart->delete();
        static::$cart = null;
	}

	public static function newCart($userId): Cart
    {
        return static::create([
            'user_id' => $userId,
            'hash' => Hash::make(time()),
            'status' => 1
        ]);
	}

	public function addProxy(int $proxyId, int $days = 1): void
    {
		$this->proxies()->attach($proxyId, [
            'count' => $days
        ]);
	}

    public function updateProxy(int $proxyId, int $days = 1): void
    {
        $this->proxies()->updateExistingPivot($proxyId, [
            'count' => $days
        ], true);
    }

	public function removeProxy($proxyId): int
    {
        return $this->proxies()->detach($proxyId);
	}

    public function proxies(): BelongsToMany
    {
        return $this->belongsToMany('App\Models\Proxy', 'cart_proxies', 'cart_id','proxy_id')
            ->using(CartProxy::class)
            ->withPivot('count')
            ->withTimestamps();
    }

}
