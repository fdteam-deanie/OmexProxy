<?php

namespace App\Http\Controllers\Api\TWA;

use App\Http\Controllers\Api\ApiController;
use App\Services\Telegram\TelegramWebAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AuthController extends ApiController
{
    public function initTelegram(Request $request, TelegramWebAppService $service)
    {
        $initData = $request->input('initData');

        $telegramUser = $service->setInitData($initData)->initUser();

        return response()->json([
            'is_enabled_auth' => !empty($telegramUser) ? $telegramUser->is_enabled_auth : false
        ]);
    }

    public function login(Request $request, TelegramWebAppService $service)
    {
        $initData = $request->input('initData');

        $success = $service->setInitData($initData)->login();

        if(!$success) {
            return response()->json(['success' => false], 401);
        }

        return response()->json(['success' => $success]);
    }

    public function updateAuthSetting(Request $request, TelegramWebAppService $service)
    {
        $initData = $request->input('initData');

        $is_enabled_auth = $request->input('is_enabled_auth');

        $service->setInitData($initData)->updateAuthSetting($is_enabled_auth);

        return response()->json([
            'is_enabled_auth' => $is_enabled_auth
        ]);
    }
}
