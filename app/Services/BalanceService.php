<?php

namespace App\Services;

use App\Exceptions\InsufficientBalanceException;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class BalanceService
{
    const CACHE_TTL = 60 * 60;

    protected User $user;

    /**
     * @param  User|null  $user
     */
    public function __construct(?User $user = null)
    {
        if(!$user) {
            $user = auth()->user();
        }
        $this->user = $user;
    }

    /**
     * @return float
     */
    public function getUserBalanceWithoutBonus(): float
    {
        return Cache::remember(Payment::CACHE_BALANCE_KEY.$this->user->id, self::CACHE_TTL, function () {
            $deposits = $this->user->payments()->onlyDeposits()->notBonus()->sum('amount');
            $bonusDeposits = $this->user->payments()->onlyDeposits()->bonus()->sum('amount');

            $withdraws = $this->user->payments()->onlySpendings()->sum('amount');
            $bonusBalance = $bonusDeposits - $withdraws;

            if($bonusBalance < 0) {
                $deposits += $bonusBalance;
            }

            return $deposits;
        });
    }

    /**
     * @return float
     */
    public function getUserBonusBalance(): float
    {
        return Cache::remember(Payment::CACHE_BONUS_BALANCE_KEY.$this->user->id, self::CACHE_TTL, function () {
            $deposits = $this->user->payments()->onlyDeposits()->bonus()->sum('amount');
            $withdraws = $this->user->payments()->onlySpendings()->sum('amount');

            return max($deposits - $withdraws, 0);
        });
    }

    /**
     * @return float
     */
    public function getUserBalance(): float
    {
        return $this->getUserBalanceWithoutBonus() + $this->getUserBonusBalance();
    }
}
