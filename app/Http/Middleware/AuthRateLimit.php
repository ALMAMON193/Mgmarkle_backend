<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AuthRateLimit
{
    public function handle(Request $request, Closure $next)
    {
        // Fingerprint for unauthenticated API
        $fp = $request->ip()
            .'|'.($request->header('User-Agent') ?? '')
            .'|'.($request->header('Accept-Language') ?? '');

        $key = 'auth_fp:'.sha1($fp);

        // Example limits (customizable per route)
        $limit = 5; // max requests
        $decay = 60; // seconds

        // Check if exceeded
        if (RateLimiter::tooManyAttempts($key, $limit)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many requests. Please wait a minute before retrying.',
            ], 429);
        }

        // Hit the rate limiter
        RateLimiter::hit($key, $decay);

        return $next($request);
    }
}
