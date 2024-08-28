<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Exceptions\RefundTimeException;
use App\Models\Payment;
use App\Models\Pivot\UserProxy;
use App\Models\Proxy;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    const CACHE_TTL = 60 * 60;
    const MAX_REFUND_TIME = 5;


    protected User $user;
    protected BalanceService $balanceService;

    /**
     * @param  User|null  $user
     */
    public function __construct(?User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->balanceService = new BalanceService($user);
    }

    /**
     * @param  float  $amount
     * @return Payment
     * @throws InsufficientBalanceException
     */
    public function pay(float $amount): Payment
    {
        if($this->balanceService->getUserBalance() < $amount) {
            throw new InsufficientBalanceException();
        }
        return Payment::create([
            'user_id' => $this->user->id,
            'amount' => $amount,
            'is_deposit' => false,
            'status' => Payment::SUCCESS
        ]);
    }

    /**
     * @param  float  $amount
     * @return Payment
     */
    public function deposit(float $amount, bool $isBonus = false): Payment
    {
        return Payment::create([
            'user_id' => $this->user->id,
            'amount' => $amount,
            'is_deposit' => true,
            'is_bonus' => $isBonus,
            'status' => Payment::SUCCESS
        ]);
    }

    /**
     * @param  float  $amount
     * @return Payment
     */
    public function bonusDeposit(float $amount): Payment
    {
        return $this->deposit($amount, true);
    }

    /**
     * @param  Payment  $payment
     * @return void
     * @throws InsufficientBalanceException
     */
    public function withdrawBonus(Payment $payment): void
    {
        $userSpendingAmount = $this->user->payments()
            ->whereDate('created_at', '>=', $payment->created_at)
            ->onlySpendings()
            ->sum('amount');

        $remainder = $payment->amount - $userSpendingAmount;

        if($remainder > 0) {
            $this->pay($remainder);
        }
    }

    /**
     * @throws RefundTimeException
     */
    public function refundProxy(Proxy $proxy)
    {
        if($proxy->pivot->paid_at->diffInMinutes(Carbon::now()) > self::MAX_REFUND_TIME) {
            Log::alert('Refund time expired ' . $proxy->id);
            throw new RefundTimeException();
        } else {
            $userProxy = UserProxy::where([
                'user_id' => $this->user->id,
                'proxy_id' => $proxy->id
            ])->first();

            $order = $userProxy->order;
            $payment = $order->payment;

            if ($order->amount > $proxy->price) {
                $order->amount -=  $proxy->price;
                $payment->amount -= $proxy->price;
                $order->save();
                $payment->save();
            } else {
                $order->delete();
                $payment->delete();
            }
            $this->user->proxies()->detach($proxy);
            Artisan::call('cache:clear');
        }
        Log::info("Proxy {$proxy->id} refunded");
    }
}
