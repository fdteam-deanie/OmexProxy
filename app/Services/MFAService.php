<?php

namespace App\Services;

use App\Mail\MFA\SendAuthCodeMail;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class MFAService
{
    const RECOVERY_CODE_TIME = 180;

    public function generateCodeForUser(User $user): int
    {
        $code = $this->generateCode();

        $user->authCodes()->create([
            'code' => Hash::make($code)
        ]);

        Mail::to($user->email)->send(new SendAuthCodeMail($code));

        return $code;
    }

    /**
     * @throws Exception
     */
    public function regenerateCodeForUser(User $user): int
    {
        $authCode = $this->getAuthCodeForUser($user);

        if(!empty($authCode) && $authCode->created_at->diffInSeconds(Carbon::now()) < self::RECOVERY_CODE_TIME) {
            throw new Exception('You can regenerate code only once in 3 minutes');
        }

        $user->authCodes()->update([
            'used' => true
        ]);

        return $this->generateCodeForUser($user);
    }

    public function verifyCodeForUser(User $user, string $code): bool
    {
        $authCode = $this->getAuthCodeForUser($user);

        if (!$authCode) {
            return false;
        }

        if (!Hash::check($code, $authCode->code)) {
            return false;
        }

        $authCode->update([
            'used' => true
        ]);

        Session::put('user_2fa', true);

        return true;
    }

    public function getLeftTimeToRegenerateCodeForUser(User $user): int
    {
        $authCode = $this->getAuthCodeForUser($user);

        if (!$authCode) {
            return 0;
        }

        $time = self::RECOVERY_CODE_TIME - $authCode->created_at->diffInSeconds(Carbon::now());

        if($time < 0) {
            return 0;
        }
        return $time;
    }

    public function getAuthCodeForUser(User $user)
    {
        return $user->authCodes()
            ->unused()
            ->latest()
            ->first();
    }

    public function generateCode(): int
    {
        if(config('app.env') !== 'production') {
            return 123456;
        }
        return random_int(100000, 999999);
    }
}
