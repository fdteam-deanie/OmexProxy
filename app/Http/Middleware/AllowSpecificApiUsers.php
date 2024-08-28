<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowSpecificApiUsers
{
    /**
     * List of allowed users' emails.
     */
    protected array $allowedUsernames = [
        'DenDevin',
        'hgjfhdgx',
        'Vlad Rybalka',
        'zavix'
    ];

    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $username = $request->input('username');

        if (in_array($username, $this->allowedUsernames)) {
            return $next($request);
        }
        return response()->json([
            'message' => 'Access denied',
            'errors' => [
                'wrong' => 'Access denied'
            ]
        ], 403);
    }
}
