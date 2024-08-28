<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Enums\CoinType;
use App\Http\Controllers\Controller;
use App\Models\CryptoWallet;
use App\Models\User;
use App\Services\Wallet\WalletApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;


class GetWalletsAction extends Controller
{
    private WalletApiService $walletApiService;

    public function __construct(WalletApiService $walletApiService)
    {
        parent::__construct();

        $this->walletApiService = $walletApiService;
    }

    public function __invoke(): JsonResponse
    {
        $json = [
            'status' => 'error'
        ];
        $responseCode = 422;


        /** @var User $user */
        $user = Auth::user();

        if (is_null($user)) {
            $json['message'] = 'User not found';
        } else {
            if(0 === $user->wallets->count()) {
                foreach (CoinType::cases() as $coinType) {
                $resp = $this->walletApiService->createWallet($coinType);
                $cryptoWallet = new CryptoWallet([
                    'title' => $resp['body']['wallet_name'],
                    'type' => $resp['body']['currency'],
                    'address' => $resp['body']['address'],
                    'user_id' => $user->id
                ]);
                $cryptoWallet->save();

                }
            }
            $wallets = CryptoWallet::where(['user_id' => $user->id])->get();
            $json['status'] = 'success';
            $json['wallets'] = $wallets;
            $responseCode = 200;
        }

        return response()->json($json, $responseCode);
    }
}
