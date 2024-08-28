<?php

namespace App\Http\Controllers\Api\Wallet;

use App\Enums\CoinType;
use App\Models\CryptoWallet;
use App\Models\User;
use App\Services\Wallet\WalletApiService;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Auth;

class GetTransactionAddressesAction
{
    private WalletApiService $walletApiService;

    public function __construct(WalletApiService $walletApiService)
    {

        $this->walletApiService = $walletApiService;
    }

    /**
     * @throws GuzzleException
     */
    public function __invoke()
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
                    $resp = $this->walletApiService->createNewAddress($coinType, $user->usename);
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
