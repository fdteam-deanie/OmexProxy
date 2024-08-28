<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CartService
{
    protected User $user;

    public function __construct(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
    }

    public function createCart(): Cart
    {
        return Cart::create([
            'user_id' => $this->user->id,
            'hash' => Hash::make(time()),
            'status' => 1
        ]);
    }
    public function getUserCart(): ?Cart
    {
        return Cart::where([
            'user_id' => $this->user->id,
            'status' => 1
        ])->with('proxies')->first();
    }

    public function cleanCart(Cart $cart): void
    {
        $cart->delete();
    }
}
