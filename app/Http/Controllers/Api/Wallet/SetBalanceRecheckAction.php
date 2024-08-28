<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Http\Controllers\Controller;
use App\Jobs\CheckCryptoBalance;
use App\Models\CryptoWallet;
use App\Services\Wallet\WalletApiService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\NoReturn;

class SetBalanceRecheckAction
{
    private WalletApiService $walletApiService;

    public function __construct(WalletApiService $walletApiService)
    {
        $this->walletApiService = $walletApiService;
    }

    public function __invoke(Request $request, string $coin)
    {
        /** @var Collection $wallets */
        $wallets = Auth::user()->wallets;
        $wallet = $wallets->first(function ($wallet) use ($coin){
            return $wallet->type == $coin;
        });

        if(is_null($wallet)) {
            return response()->json('Invalid currency', 422);
        }

        CheckCryptoBalance::dispatch($wallet);
        return response()->json([
            'status' => 'success',
            'message' => 'After confirmation of payment, the amount will be updated automatically'
            ]);
    }
}
