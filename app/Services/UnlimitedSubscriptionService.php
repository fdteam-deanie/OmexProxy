<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Cart;
use App\Models\Proxy;
use App\Models\Setting;
use App\Models\UnlimitedSubscription;
use App\Models\User;
use Exception;

class UnlimitedSubscriptionService
{
    const DEFAULT_PRICE = 70;

    protected User $user;
    protected OrderService $orderService;
    protected CartService $cartService;

    /**
     * @param  User|null  $user
     */
    public function __construct(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->orderService = new OrderService($user);
        $this->cartService = new CartService($user);
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function createSubscription(): UnlimitedSubscription
    {
        $order = $this->orderService->createOrder($this->getPrice());
        $this->orderService->payForOrder($order);
        $cart = $this->cartService->getUserCart();

        $subscription = new UnlimitedSubscription();

        $subscription->expired_at = now()->addDay();

        $subscription->user()->associate($this->user);
        $subscription->order()->associate($order);
        $subscription->save();

        if(!is_null($cart)) {
            $this->attachProxiesFromCart($subscription, $cart);
        }

        return $subscription;
    }

    public function deactivateUserActiveSubscription(): void
    {
        $subscription = $this->getUserActiveSubscription();
        if(!is_null($subscription)) {
            $subscription->active = false;
            $subscription->save();
        }
    }

    /**
     * @throws InsufficientBalanceException
     * @throws Exception
     */
    public function renewCurrentUserSubscription(): UnlimitedSubscription
    {
        $subscription = $this->getUserActiveSubscription();
        if(is_null($subscription)) {
            throw new Exception('User has no active subscription');
        }
        $order = $this->orderService->createOrder($this->getPrice());
        $this->orderService->payForOrder($order);

        $subscription->order()->associate($order);
        $subscription->expired_at = $subscription->expired_at->addDay();

        $subscription->save();

        return $subscription;
    }

    public function attachProxiesFromCart(UnlimitedSubscription $subscription, Cart $cart, bool $cleanCart = true): void
    {
        $subscription->user->proxies()->syncWithoutDetaching($cart->proxies->map(function ($proxy) use ($subscription) {
            return [
                'proxy_id' => $proxy->id,
                'order_id' => $subscription->order_id,
                'unlimited_subscription_id' => $subscription->id,
                'is_paid' => true,
                'paid_at' => now(),
                'expired_at' => $subscription->expired_at,
            ];
        })->keyBy('proxy_id')->toArray());
        $subscription->save();
        if($cleanCart)
        {
            $this->cartService->cleanCart($cart);
        }
    }

    public function attachProxy(UnlimitedSubscription $subscription, int $proxyId): void
    {
        $subscription->user->proxies()->syncWithoutDetaching([
            $proxyId => [
                'order_id' => $subscription->order_id,
                'unlimited_subscription_id' => $subscription->id,
                'is_paid' => true,
                'paid_at' => now(),
                'expired_at' => $subscription->expired_at,
            ]
        ]);
        $subscription->save();
    }

    public function getUserActiveSubscription(): ?UnlimitedSubscription
    {
        return $this->user->unlimitedSubscriptions()
            ->where('expired_at', '>', now())
            ->where('active', true)
            ->first();
    }

    public function isUserHasActiveSubscription(): bool
    {
        return $this->user->unlimitedSubscriptions()
            ->where('expired_at', '>', now())
            ->where('active', true)
            ->exists();
    }

    public function getPrice(): float
    {
        return Setting::get('unlimited_subscription_price', self::DEFAULT_PRICE, 'unlimited_subscription');
    }
}
