<?php

namespace App\Jobs;

use App\Enums\CoinType;
use App\Models\CryptoWallet;
use App\Models\Currency;
use App\Models\Payment;
use App\Services\Wallet\WalletApiService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class CheckCryptoBalance implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private WalletApiService $walletApiService;
    private CryptoWallet $wallet;
    private int $tries = 20;

    /**
     * Create a new job instance.
     */
    public function __construct(CryptoWallet $wallet)
    {
        $this->wallet = $wallet;
    }

    /**
     * Execute the job.
     * @throws GuzzleException
     */
    public function handle(): void
    {
        $coinCode = $this->wallet->type;
        $coinType = Arr::first(CoinType::cases(), function (CoinType $coinType) use ($coinCode){
            return $coinType->value == $coinCode;
        });
        $walletApiService = new WalletApiService();

        $response = $walletApiService->setWallet($this->wallet->title)->getBalanceByCoinType($coinType);
        if ($response['status'] == 'success') {
            $walletInfo = $response['body'];
            if ($this->wallet->amount == $walletInfo['balance']) {
                if ($this->attempts() < $this->tries) {
                    Log::info('Проводиться перевірка балансу криптогаманця...');
                    $this->release(120); // Повторна спроба через 10 секунд (можна налаштувати)
                } else {
                    // Якщо кількість спроб досягла ліміту, видаліть задачу з черги
                    $this->delete();
                    Log::error('Перевищено ліміт спроб. Задачу видалено з черги.');
                }
            } else if($this->wallet->amount < $walletInfo['balance']) {
                $currency = Currency::where(['name' => $coinCode])->first();
                $paymentAmount = ($walletInfo['balance'] - $this->wallet->amount) * $currency->exchange_rate;
                $this->increaseBalance($this->wallet->user_id, $paymentAmount);
                $this->wallet->amount = $walletInfo['balance'];
                $this->wallet->save();
            }
        }
    }

    public function increaseBalance(int $userId, float $amount)
    {
        return Payment::create([
            'user_id' => $userId,
            'is_deposit' => true,
            'amount' => $amount,
            'status' => Payment::SUCCESS,
        ]);
    }
}
