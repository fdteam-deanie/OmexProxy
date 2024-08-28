<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CustomAuthApi
{
    /**
     * List of allowed users' emails.
     */
    protected $allowedEmails = [
        'admin@devinit.site',
        'dendevinn@gmail.com',
        'flutterflu1@gmail.com',
        'vlad.rybalka@gmail.com',
        'zavix1988@gmail.com',
    ];

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!Auth::guard('api')->check()) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        $user = Auth::guard('api')->user();
        if ($user && in_array($user->email, $this->allowedEmails)) {
            return $next($request);
        }

        return response()->json(['message' => 'Access denied.'], 403);
    }
}
