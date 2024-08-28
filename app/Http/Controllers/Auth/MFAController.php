<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\MFAService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

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

    public function index()
    {
        if (Session::has('user_2fa')) {
            return redirect()->route('nova.pages.home');
        }
        if($this->user->authCodes()->unused()->count() == 0) {
            $this->mfaService->generateCodeForUser($this->user);
        }
        return view('auth.mfa');
    }
}
