<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string ...$roles
     * @return Response
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (! $user || ! in_array($user->user_type, $roles)) {
            return response()->json([
                'success' => false,
                'message' => "Unauthorized access. Your role '{$user?->user_type}' is not authorized to access this resource.",
            ], 403);
        }

        return $next($request);
    }
}
