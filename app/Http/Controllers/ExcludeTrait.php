<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CountryIp;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Support\Facades\DB;

trait ExcludeTrait
{
    private function getExcludeProxyIds(User $user): array
    {
        $cart = Cart::getCart($user->id);
        $exclude = [];

        if ($cart->proxies->isNotEmpty()) {
            $exclude = $cart->proxies->pluck('id')->toArray();
        }

        $userProxyIds = $user->proxies()
            ->wherePivot('is_paid', true)
            ->get()
            ->pluck('id')
            ->toArray();

        $excludedByUsersCount = Proxy::withCount('users')
            ->having('max_users_count', '<=', \DB::raw('users_count'))->pluck('id')
            ->toArray();

        return array_merge($exclude, $userProxyIds, $excludedByUsersCount);
    }
}
