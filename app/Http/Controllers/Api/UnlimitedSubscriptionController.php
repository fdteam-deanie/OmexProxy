<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\InsufficientBalanceException;
use App\Services\UnlimitedSubscriptionService;
use Illuminate\Http\Request;

class UnlimitedSubscriptionController extends ApiController
{
    protected UnlimitedSubscriptionService $service;

    public function boot()
    {
        $this->service = new UnlimitedSubscriptionService();
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function subscribe(Request $request)
    {
        if($this->service->isUserHasActiveSubscription()) {
            return response()->json([
                'message' => 'User already has active unlimited subscription'
            ], 400);
        }
        $this->service->createSubscription();

        return response()->json([
            'message' => 'Subscription created successfully'
        ]);
    }

    public function unsubscribe()
    {
        $this->service->deactivateUserActiveSubscription();
        return response()->json([
            'message' => 'Subscription deactivated successfully'
        ]);
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function renew()
    {
        $this->service->renewCurrentUserSubscription();
        return response()->json([
            'message' => 'Subscription renewed successfully'
        ]);
    }

    public function getActiveSubscription()
    {
        $subscription = $this->service->getUserActiveSubscription();
        $price = $this->service->getPrice();

        if(is_null($subscription)) {
            return response()->json([
                'price' => $price,
                'subscription' => null,
                'hasActiveSubscription' => false
            ]);
        }

        return response()->json([
            'hasActiveSubscription' => true,
            'price' => $price,
            'subscription' => [
                'id' => $subscription->id,
                'expired_at' => $subscription->expired_at->format('d.m.Y H:i'),
            ]
        ]);
    }

    public function getPrice()
    {
        return response()->json([
            'price' => $this->service->getPrice()
        ]);
    }
}
