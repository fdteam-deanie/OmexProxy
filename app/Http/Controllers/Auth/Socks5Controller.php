<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Socks5Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class Socks5Controller extends Controller
{
    public function updateSocks5(Socks5Request $request): JsonResponse
    {

        Auth::user()->update([
            'socks5_username' => $request->input('username'),
            'socks5_password' => $request->input('password')
        ]);

        return response()->json(['status' => 'success', 'message' => 'Credentials updated!'], 200);

    }

    public function getSocks5(): JsonResponse
    {

        $user = Auth::user();

        if (!is_null($user)) {
            $json = [

                'status' => 'success',
                'result' => [
                    'username' => $user->socks5_username,
                    'password' => $user->socks5_password,
                ],

            ];
            return response()->json($json);
        }
        return response()->json(['status' => 'error', 'errors' => ['unauthorized'] ], 401);

    }
}
