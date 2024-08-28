<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\ApiController;
use App\Http\Resources\Payments\PaymentsResource;
use App\Http\Resources\Proxy\ProxyDetailResource;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentsController extends ApiController
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $count = $request->input('count') ?? 25;

        $payments = Payment::where(['user_id' => $user->id])
            ->orderBy('id', 'desc')
            ->paginate($count);

        $paymentsCollection = $payments->getCollection();
        $payments = $payments->setCollection(
            PaymentsResource::collection($paymentsCollection)
                ->collection
        );

        return response()->json(['status' => 'success', 'payments' => $payments]);

    }
}
