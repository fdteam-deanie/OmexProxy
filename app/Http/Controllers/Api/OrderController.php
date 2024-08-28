<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\EmptyCartException;
use App\Exceptions\EmptySock5CredentialsException;
use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\ProxyAlreadyPurchasedException;
use App\Http\Requests\Order\QuickBuyProxyRequest;
use App\Http\Requests\Order\RenewProxyRentalRequest;
use App\Http\Resources\Proxy\ProxyDetailResource;
use App\Models\Proxy;
use App\Models\User;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\Proxy\UserProxyService;
use App\Services\ProxyHost\ProxyHostWebhooksService;
use App\Services\UnlimitedSubscriptionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends ApiController
{
    protected User $user;
    protected OrderService $orderService;
    protected CartService $cartService;
    protected UnlimitedSubscriptionService $unlimitedSubscriptionService;
    protected UserProxyService $userProxyService;

    public function boot()
    {
        $this->user = auth()->user();
        $this->orderService = new OrderService($this->user);
        $this->cartService = new CartService($this->user);
        $this->unlimitedSubscriptionService = new UnlimitedSubscriptionService($this->user);
        $this->userProxyService = new UserProxyService($this->user);
    }

    /**
     * @throws EmptySock5CredentialsException
     * @throws EmptyCartException
     * @throws InsufficientBalanceException
     */
    public function order(Request $request): JsonResponse
    {
        if(empty($this->user->socks5_username) || empty($this->user->socks5_password)){
            throw new EmptySock5CredentialsException();
        }

        $cart = $this->cartService->getUserCart();

        if(empty($cart) || $cart->proxies->isEmpty()) {
            throw new EmptyCartException();
        }

        $orderedProxyIds = $cart->proxies->pluck('id')->toArray();

        $activeSubscription = $this->unlimitedSubscriptionService->getUserActiveSubscription();
        if(!empty($activeSubscription)) {
            $this->unlimitedSubscriptionService->attachProxiesFromCart($activeSubscription, $cart);
        } else {
            $amount = $cart->total;
            $order = $this->orderService->createOrder($amount);
            $this->orderService->payForOrder($order);
            $this->userProxyService->addProxiesFromCart($cart, $order);
        }

        $orderedProxies = $this->user->proxies->whereIn('id', $orderedProxyIds);

        return response()->json([
            'status' => 'success',
            'message' => 'Success! Order confirmed!',
            'cartItems' => [],
            'cartTotal' => 0,
            'orderedProxies' => ProxyDetailResource::collection($orderedProxies)->resolve()
        ]);
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function quickBuyProxy(QuickBuyProxyRequest $request): JsonResponse
    {
        $proxy = $this->user->proxies()->find($request->proxy_id);

        if(!empty($proxy) && $proxy->pivot->is_paid) {
            throw new ProxyAlreadyPurchasedException();
        }

        $activeSubscription = $this->unlimitedSubscriptionService->getUserActiveSubscription();
        if(!empty($activeSubscription)) {
            $this->unlimitedSubscriptionService->attachProxy($activeSubscription, $request->proxy_id);
        } else {
            $this->orderService->quickBuyProxy(
                $request->proxy_id,
                $request->days
            );
        }

        $proxy = $this->user->proxies()->find($request->proxy_id);

        return response()->json([
            'message' => 'Proxy bought successfully! Your rental will expire at: '.$proxy->pivot->expired_at->format('Y-m-d H:i'),
            'proxy' => ProxyDetailResource::make($proxy)->resolve(),
            'expired_at' => $proxy->pivot->expired_at->format('Y-m-d H:i')
        ]);
    }

    public function renewRental(RenewProxyRentalRequest $request): JsonResponse
    {
        $this->orderService->renewRental(
            $request->proxy_id,
            $request->days
        );

        $proxy = $this->user->proxies()->find($request->proxy_id);

        return response()->json([
            'message' => 'Rental renewed successfully! Your rental will expire at: '.$proxy->pivot->expired_at->format('Y-m-d H:i'),
            'expired_at' => $proxy->pivot->expired_at->format('Y-m-d H:i')
        ]);
    }
}
