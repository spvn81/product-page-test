<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckBearerToken
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
        $authorizationHeader = $request->header('Authorization');

        // Check if the Authorization header is missing or doesn't start with "Bearer"
        if (!$authorizationHeader || strpos($authorizationHeader, 'Bearer ') !== 0) {
            return response()->json(['error' => 'Unauthorized. Bearer token is missing.'], 401);
        }

        // If the Authorization header is present and starts with "Bearer", continue to the next middleware
        return $next($request);
    }
}
