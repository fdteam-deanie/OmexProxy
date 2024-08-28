<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Models\User;
use App\Services\TrialPeriodService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class RegistrationController extends Controller
{
    public function register(RegisterUserRequest $request): JsonResponse
    {
        $newUser = new User;

        $newUser->fill([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ])->save();

        $trialService = new TrialPeriodService($newUser);
        $trialService->createTrialPeriod();

        return response()->json(['status' => 'success', 'message' => 'Your account has been created!'], 200);
    }
}
