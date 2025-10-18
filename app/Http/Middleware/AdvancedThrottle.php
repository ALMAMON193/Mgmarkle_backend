<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AdvancedThrottle
{
    use ApiResponse;

    public function handle(Request $request, Closure $next)
    {
        // Authenticated user branch
        if ($request->user()?->id) {
            $key = 'user:'.$request->user()->id;
            $max = 60; // max requests per minute
            $decay = 120; // 1 minute

            if (RateLimiter::tooManyAttempts($key, $max)) {
                return $this->buildLimitResponse($request);
            }
            RateLimiter::hit($key, $decay);

            return $next($request);
        }
        // Unauthenticated users: fingerprint + per-IP
        $fp = $request->ip()
            .'|'.($request->header('User-Agent') ?? '')
            .'|'.($request->header('Accept-Language') ?? '');

        $fingerKey = 'fp:'.sha1($fp);
        $ipKey = 'ip:'.$request->ip();

        // Fingerprint strong limit
        if (RateLimiter::tooManyAttempts($fingerKey, 20)) {
            return $this->buildLimitResponse($request);
        }
        RateLimiter::hit($fingerKey, 60);

        // IP soft limit
        if (RateLimiter::tooManyAttempts($ipKey, 10)) {
            return $this->buildLimitResponse($request);
        }
        RateLimiter::hit($ipKey, 60);

        return $next($request);
    }

    protected function buildLimitResponse(Request $request): Response
    {
        return $this->sendResponse([], 'Too many requests');
    }
}
