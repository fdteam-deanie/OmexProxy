<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBalanceRequest;
use App\Models\Payment;
use App\Models\User;
use App\Services\BalanceService;
use App\Services\PaymentService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class BalanceController extends Controller
{
  protected ?User $user;
  protected PaymentService $paymentService;
  protected BalanceService $balanceService;

  public function boot()
  {
    $this->user = auth()->user();
    $this->paymentService = new PaymentService($this->user);
    $this->balanceService = new BalanceService($this->user);
  }

  public function getBalance(): JsonResponse
  {
    if (is_null($this->user)) {
      return response()->json(['status' => 'error', 'message' => 'Unauthorized!'], 401);
    }

    $balance = $this->balanceService->getUserBalance();
    $bonusBalance = $this->balanceService->getUserBonusBalance();
    $balanceWithoutBonus = $this->balanceService->getUserBalanceWithoutBonus();

    return response()->json([
      'status' => 'success',
      'balance' => $balance,
      'bonus_credits' => $bonusBalance,
      'user_credits' => $balanceWithoutBonus,
    ]);
  }

  public function increaseBalance(StoreBalanceRequest $request): JsonResponse
  {
    $userId = $request->input('user_id');
    $amount = $request->input('amount');

    if (empty($userId)) {
      $this->boot();
    } else {
      $user = User::find($userId);
      $this->paymentService = new PaymentService($user);
      if (!$user) {
        return response()->json([
          'status' => 'error',
          'message' => 'User not found!',
        ], 404);
      }
    }

    try {
      $this->paymentService->deposit($amount);
    } catch (\Exception $e) {
      Log::error('Deposit failed for user ID ' . $userId . ': ' . $e->getMessage());
      return response()->json([
        'status' => 'error',
        'message' => 'Deposit failed!',
      ], 500);
    }

    return response()->json([
      'status' => 'success',
      'message' => 'Thank you! Your deposit is confirmed!',
    ]);
  }
}
