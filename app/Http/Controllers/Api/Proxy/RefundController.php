<?php

namespace App\Http\Controllers\Api\Proxy;

use App\Exceptions\RefundTimeException;
use App\Http\Controllers\Controller;
use App\Services\PaymentService;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;

class RefundController extends Controller
{

    private ?Authenticatable $user;
    private PaymentService $paymentService;

    public function boot(): void
    {
        $this->user = auth()->user();
        $this->paymentService = new PaymentService($this->user);
    }

    /**
     * @param int $proxyId
     * @return JsonResponse
     */
    public function index(int $proxyId): JsonResponse
    {
        $response = [
            'status' => 'error',
            'message' => 'hz wo za owibka'
        ];

        $proxy = $this->user->proxies()->findOrFail($proxyId);
        try {
            $this->paymentService->refundProxy($proxy);
        } catch (RefundTimeException $exception) {
            $response['message'] = $exception->getMessage();
        }

        $response = [
            'status' => 'success',
            'message' => 'Proxy refunded'
        ];

        return response()->json($response);
    }
}
