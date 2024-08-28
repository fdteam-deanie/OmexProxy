<?php

namespace App\Services\Telegram;

use App\Models\TelegramUser;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TelegramWebAppService
{
    protected string $botToken;

    protected WebAppInitData $appData;

    public function __construct()
    {
        $this->botToken = env('TELEGRAM_BOT_TOKEN');
    }

    public function initUser(): ?TelegramUser
    {
        $user = request()->user();

        if(empty($user)) {
            return null;
        }

        $telegramId = $this->appData->getUser()->getId();

        $tgUser = TelegramUser::where('telegram_id', $telegramId)->first();
        if(!empty($tgUser)){
            return $tgUser;
        }

        return $user->telegramUsers()->create([
            'telegram_id' => $telegramId,
            'is_enabled_auth' => false
        ]);
    }

    public function updateAuthSetting(bool $is_enabled_auth): void
    {
        $telegramId = $this->appData->getUser()->getId();

        TelegramUser::where('telegram_id', $telegramId)->update([
            'is_enabled_auth' => $is_enabled_auth
        ]);
    }

    public function login(): mixed
    {
        $telegramId = $this->appData->getUser()->getId();
        $tgUser = TelegramUser::where('telegram_id', $telegramId)->first();

        if(empty($tgUser) || $tgUser->is_enabled_auth === false){
            return null;
        }

        $tokenResult = $tgUser->user->createToken('Personal Access Token');

        $token = $tokenResult->token;
        $token->expires_at = Carbon::now()->addWeeks(2);
        $token->save();

        return [
            'access_token' => $tokenResult->accessToken,
            'url' => session()->pull('previousUrl'),
            'user' => $tgUser->user,
            'expires_in' => $token->expires_at
        ];
    }

    public function setInitData(string $initData): static
    {
        if(!$this->checkInitData($initData)){
            return $this;
        }
        $this->appData = $this->parseInitData($initData);
        return $this;
    }

    protected function parseInitData(string $initData): WebAppInitData
    {
        $explodedInitData = explode('&', rawurldecode($initData));

        $webAppInitDataArray = [];

        foreach ($explodedInitData as $item) {
            $item = explode('=', $item);
            $webAppInitDataArray[$item[0]] = $item[1];
        }


        Log::info('data', $webAppInitDataArray);

        return WebAppInitData::fromArray($webAppInitDataArray);
    }

    protected function checkInitData(string $initData): bool
    {
        $data_check_arr = explode('&', rawurldecode($initData));
        $needle = 'hash=';
        $check_hash = FALSE;
        foreach($data_check_arr AS &$val){
            if(substr($val, 0, strlen($needle)) === $needle){
                $check_hash = substr_replace($val, '', 0, strlen($needle));
                $val = NULL;
            }
        }

        $data_check_arr = array_filter($data_check_arr);
        sort($data_check_arr);

        $initData = implode("\n", $data_check_arr);
        $secret_key = hash_hmac('sha256', $this->botToken, "WebAppData", true);
        $hash = bin2hex(hash_hmac('sha256', $initData, $secret_key, true) );

        if(!strcmp($hash, $check_hash) === 0){
            return false;
        }

        return true;
    }
}
