<?php

namespace App\Services;

use App\Exceptions\EmptyCartException;
use App\Exceptions\InsufficientBalanceException;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Proxy;
use App\Models\User;
use App\Services\Proxy\UserProxyService;
use Illuminate\Support\Collection;

class OrderService
{
    protected User $user;
    protected PaymentService $paymentService;
    protected CartService $cartService;
    protected UserProxyService $userProxyService;

    /**
     * @param  User|null  $user
     */
    public function __construct(?User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->paymentService = new PaymentService($user);
        $this->cartService = new CartService($user);
        $this->userProxyService = new UserProxyService($user);
    }

    public function createOrder(float $amount): ?Order
    {
        return Order::create([
            'user_id' => $this->user->id,
            'amount' => $amount,
            'status' => Order::PENDING
        ]);
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function payForOrder(Order $order): Payment
    {
        try {
            $payment = $this->paymentService->pay($order->amount);
        } catch (InsufficientBalanceException $e) {
            $order->status = Order::FAILED;
            $order->save();
            throw $e;
        }

        $payment->order()->associate($order);
        $payment->save();

        $order->status = Order::SUCCESS;
        $order->save();

        return $payment;
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function quickBuyProxy(int $proxyId, int $rentDays = 1): Proxy
    {
        $proxy = Proxy::findOrFail($proxyId);

        $amount = $proxy->price * $rentDays;
        $order = $this->createOrder($amount);
        $this->payForOrder($order);

        $this->userProxyService->addProxy($proxy, $order, $rentDays);

        return $proxy;
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function renewRental(int $proxyId, int $rentDays = 1): Proxy
    {
        $proxy = $this->user->proxies()->findOrFail($proxyId);

        $amount = $proxy->price * $rentDays;
        $order = $this->createOrder($amount);
        $this->payForOrder($order);

        $this->user->proxies()->updateExistingPivot($proxy->id, [
            'order_id' => $order->id,
            'paid_at' => now(),
            'unlimited_subscription_id' => null,
            'expired_at' => $proxy->pivot->expired_at->addDays($rentDays)
        ]);

        return $proxy;
    }
}
