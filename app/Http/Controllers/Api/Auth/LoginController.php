<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\PersonalAccessTokenResult;
use Illuminate\Support\Facades\Hash;

class LoginController
{
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->only(['username', 'password']);

        $user = User::where('username', $credentials['username'])->first();
        if(!$user || !Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
                'errors' => ['wrong' => 'Wrong username or password']
            ], 401);
        }

        $tokenResult = $user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(2);
        $token->save();

        $success = [
            'access_token' => $tokenResult->accessToken,
            'url' => session()->pull('previousUrl'),
            'user' => $user,
            'expires_in' => $token->expires_at
        ];

        //Auth::guard('api')->login($user);

        return response()->json(['success' => $success]);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->token()->revoke();

        $guards = array_keys(config('auth.guards'));

        foreach ($guards as $guard) {
            $guard = Auth::guard($guard);

            if ($guard instanceof \Illuminate\Auth\SessionGuard) {
                $guard->logout();
            }
        }

        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }
}
