<?php

namespace Zavix\WalletsWithdrawTool\Http\Controllers;

use App\Enums\CoinType;
use App\Services\Wallet\WalletApiService;
use Illuminate\Routing\Controller;
use Illuminate\Support\Arr;
use Inertia\Response;
use Inertia\ResponseFactory;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Nova;

class WalletsWithdrawToolController extends Controller
{
    private WalletApiService $walletApiService;

    public function __construct(WalletApiService $walletApiService)
    {

        $this->walletApiService = $walletApiService;
    }
    public function index(NovaRequest $request): Response|ResponseFactory
    {
        $balances = [];
        foreach (CoinType::cases() as $coinType) {
            $response = $this->walletApiService->setWallet(env('CRYPTO_WALLET_NAME'))->getBalanceByCoinType($coinType);

            $balances[$coinType->value] = $response['body']['balance'];
        }

        return inertia('WalletsWithdrawTool', compact('balances'));
    }

    public function withdraw(NovaRequest $request)
    {
        $coin = $request->input('coin');
        $amount = $request->input('amount');
        $json = [
            'status' => 'error',
        ];

        $coinType = Arr::first(CoinType::cases(), function (CoinType $coinType) use ($coin){
            return $coinType->value == $coin;
        });
        try {
            $response = $this->walletApiService->setWallet(env('CRYPTO_WALLET_NAME'))->withdraw($coinType, $amount);

            if($response['status'] == 'success') {
                $json = [
                    'status' => 'success',
                    'message' => 'Sent for processing'
                ];
            } else {
                $json = [
                    'status' => 'error',
                    'message' => $response['message']
                ];
            }
        } catch (\Exception $e) {
            $json['message'] = $e->getMessage();
        }

        return response()->json($json);
    }
}
