<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Laravel\Passport\Token;

class TokenController extends Controller
{
    public function refresh(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        /** @var Token $token */
        $token = $user->token();
        $token->expires_at = Carbon::now()->addWeeks(2);
        $token->save();

        return response()->json($user);
    }

}
