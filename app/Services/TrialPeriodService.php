<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Setting;
use App\Models\TrialPeriod;
use App\Models\User;

class TrialPeriodService
{
    const DEFAULT_DEPOSIT_AMOUNT = 100;
    const DEFAULT_DURATION = 7;

    protected User $user;
    protected PaymentService $paymentService;

    /**
     * @param  User|null  $user
     */
    public function __construct(User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
        $this->paymentService = new PaymentService($user);
    }

    public function createTrialPeriod(): TrialPeriod
    {
        $depositAmount = $this->getTrialDepositAmount();
        $payment = $this->paymentService->bonusDeposit($depositAmount);

        $duration = $this->getTrialDuration();

        $trialPeriod = new TrialPeriod();
        $trialPeriod->expired_at = now()->addDays($duration);
        $trialPeriod->user()->associate($this->user);
        $trialPeriod->payment()->associate($payment);
        $trialPeriod->save();

        return $trialPeriod;
    }

    /**
     * @throws InsufficientBalanceException
     */
    public function deactivateTrialPeriod(TrialPeriod $trialPeriod): void
    {
        $this->paymentService->withdrawBonus($trialPeriod->payment);

        $trialPeriod->active = false;
        $trialPeriod->save();
    }

    public function getUserActiveTrialPeriod(): ?TrialPeriod
    {
        return $this->user->trialPeriods()->active()->first();
    }

    public function getTrialDepositAmount(): int
    {
        return Setting::get('trial_deposit_amount', self::DEFAULT_DEPOSIT_AMOUNT, 'trial');
    }

    public function getTrialDuration(): int
    {
        return Setting::get('trial_duration', self::DEFAULT_DURATION, 'trial');
    }
}
