<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MeController extends Controller
{
    public function index(Request $request): \Illuminate\Http\JsonResponse
    {
        $user = $request->user();

        if (empty($user)) {
            return response()->json(false);
        }

        if(Auth::guest()) {
            Auth::login($user);
        }

        return response()->json($user);
    }
}
