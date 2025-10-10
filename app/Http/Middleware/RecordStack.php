<?php

namespace App\Http\Middleware;

use App\Models\StackCount;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecordStack
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $today = Carbon::today()->toDateString();

            StackCount::firstOrCreate(
                ['user_id' => $user->id, 'date' => $today],
                ['time' => now()]
            );
        }

        return $next($request);
    }
}
