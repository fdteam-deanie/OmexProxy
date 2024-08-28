<?php

namespace App\Http\Middleware;

use App\Services\MFAService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class Check2FA
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Session::has('user_2fa') && !empty(auth()->guard('nova')->user())) {
            return redirect()->route('mfa');
        }
        return $next($request);
    }
}
