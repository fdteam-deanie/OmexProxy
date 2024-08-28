<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictIpMiddleware
{
  /**
   * Allowed IP-addresses.
   *
   * @var array
   */
  protected array $allowedIps = [
    '192.168.56.1',
    '195.158.198.247',
    '37.25.105.119',
    '209.198.138.76',
    '91.231.202.200',
    
    //TWA
    '185.68.16.133'
  ];

  /**
   * Handle an incoming request.
   *
   * @param Closure(Request): (Response) $next
   */
  public function handle(Request $request, Closure $next): Response
  {
    if (!in_array($request->ip(), $this->allowedIps)) {
      return response()->json(false, 403);
    }

    return $next($request);
  }

}
