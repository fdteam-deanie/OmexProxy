<?php

namespace App\Services\Proxy;

use App\Models\Cart;
use App\Models\Order;
use App\Models\Proxy;
use App\Models\User;
use App\Services\CartService;
use App\Services\ProxyHost\ProxyHostWebhooksService;
use Illuminate\Support\Collection;

class UserProxyService
{
    protected User $user;
    protected CartService $cartService;
    protected ProxyHostWebhooksService $proxyHostWebhooksService;

    public function __construct(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->cartService = new CartService($user);
        $this->proxyHostWebhooksService = new ProxyHostWebhooksService($user);
    }

    public function addProxiesFromCart(Cart $cart, Order $order, bool $cleanCart = true): array
    {
        $proxies =  $this->user->proxies()->syncWithoutDetaching(
            $cart->proxies->map(function ($proxy) use ($order) {
                $credentials = $proxy->getCredentials($this->user);

                return [
                    'proxy_id' => $proxy->id,
                    'is_paid' => true,
                    'order_id' => $order->id,
                    'paid_at' => now(),
                    'expired_at' => now()->addDays($proxy->pivot->count),
                    'connect_host' => $proxy->getConnectionHost(),
                    'username' => $credentials['username'] ?? '',
                    'password' => $credentials['password'] ?? ''
                ];
            })->keyBy('proxy_id')->toArray()
        );
//        $this->grantUserAccessToProxies($this->user, $cart->proxies);

        $this->cartService->cleanCart($cart);
        return $proxies;
    }

    public function addProxy(Proxy $proxy, Order $order, int $rentDays = 1): array
    {
        $credentials = $proxy->getCredentials($this->user);

        $result = $this->user->proxies()->syncWithoutDetaching([
            $proxy->id => [
                'is_paid' => true,
                'order_id' => $order->id,
                'paid_at' => now(),
                'unlimited_subscription_id' => null,
                'expired_at' => now()->addDays($rentDays),
                'connect_host' => $proxy->getConnectionHost(),
                'username' => $credentials['username'] ?? '',
                'password' => $credentials['password'] ?? ''
            ]
        ]);

//        $this->grantUserAccessToProxy($this->user, $proxy);

        return $result;
    }

    public function grantUserAccessToProxies(User $user, Collection $proxies): void
    {
        $proxies->each(function ($proxy) use ($user) {
            $provider = $proxy->getProvider();
            if(!empty($provider)) {
                $provider->grantUserAccessToProxy($user, $proxy);
            }
        });
    }

    public function grantUserAccessToProxy(User $user, Proxy $proxy): void
    {
        $this->grantUserAccessToProxies($user, collect([$proxy]));
    }
}
