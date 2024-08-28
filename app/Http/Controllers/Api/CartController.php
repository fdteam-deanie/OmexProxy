<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Cart\AddToCartRequest;
use App\Models\Cart;
use App\Models\Proxy;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Vite;

class CartController extends ApiController
{
    protected User $user;
    public function boot()
    {
        $this->user = auth()->user();
    }

    public function addToCart(AddToCartRequest $request) {

        $proxyId = $request->proxyId;

        $cart = Cart::getCart($this->user->id);
        $cart->addProxy($proxyId);
        $cart->load('proxies');

        return $this->respondWithCart($cart);
    }

    public function updateCart(Request $request)
    {
        $proxyId = $request->proxyId;
        $days = $request->days;

        $cart = Cart::getCart($this->user->id);
        $cart->updateProxy($proxyId, $days);
        $cart->load('proxies');

        return $this->respondWithCart($cart);
    }

    public function removeFromCart(Request $request) {

        $proxyId = $request->proxyId;

        $cart = Cart::getCart($this->user->id);
        $cart->removeProxy($proxyId);
        $cart->load('proxies');

        return $this->respondWithCart($cart);
    }

    public function getCart()
    {
        $cart = Cart::getCart($this->user->id);

        return $this->respondWithCart($cart);
    }

    public function respondWithCart(Cart $cart): JsonResponse
    {

        return response()->json([
            'status' => 'success',
            'cartItems' => $cart->proxies->map(function ($proxy) {

                $fileName = $proxy->country->code ? strtolower($proxy->country->code) : 'undefined';

                return [
                    'id' => $proxy->id,
                    'ip' => $proxy->ip,
                    'country_data' => [
                        'id' => $proxy->country->id,
                        'name' => $proxy->country->name,
                        'flag' => Vite::asset("resources/images/flags/{$fileName}.png")
                    ],
                    'city_data' => [
                        'id' => $proxy->city->id ?? null,
                        'name' => $proxy->city->name ?? null,
                    ],
                    'is_static' => (bool) $proxy->is_static,
                    'price' => $proxy->price,
                    'days' => $proxy->pivot->count,
                ];
            }),
            'cartTotal' => $cart->total,
        ]);
    }
}
