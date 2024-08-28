<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MFA\VerifyCodeRequest;
use App\Models\User;
use App\Services\MFAService;

class MFAController extends Controller
{
    protected MFAService $mfaService;
    protected User $user;

    public function boot()
    {
        app()->setLocale('ru');
        $this->mfaService = app(MFAService::class);
        $this->user = auth()->guard('nova')->user();
    }

    public function getLeftTimeToRegenerateCode()
    {
        $time = $this->mfaService->getLeftTimeToRegenerateCodeForUser($this->user);

        return response()->json([
            'leftTime' => $time
        ]);
    }

    /**
     * @throws \Exception
     */
    public function regenerateCode()
    {
        $this->mfaService->regenerateCodeForUser($this->user);
        $leftTime = $this->mfaService->getLeftTimeToRegenerateCodeForUser($this->user);

        return response()->json([
            'message' => 'Code was regenerated',
            'leftTime' => $leftTime
        ]);
    }

    public function verifyCode(VerifyCodeRequest $request)
    {
        $isVerified = $this->mfaService->verifyCodeForUser($this->user, $request->code);

        if(!$isVerified) {
            return response()->json([
                'message' => __('Incorrect code')
            ], 422);
        }

        return response()->json([
            'message' => 'Code was verified',
            'url' => route('nova.pages.home')
        ]);
    }
}
