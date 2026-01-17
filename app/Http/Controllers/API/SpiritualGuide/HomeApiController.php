<?php

namespace App\Http\Controllers\API\SpiritualGuide;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpiritualGuide\HomeResource;
use App\Models\Event;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class HomeApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        // Cache key per user
        $cacheKey = "spiritual_guide_home_{$user->id}";

        // Cache data for 10 minutes
        $data = Cache::remember($cacheKey, now()->addMinutes(1), function () use ($user, $today) {
            return [
                'today_schedule' => Event::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->orderBy('start_time')
                    ->orderBy('created_at', 'desc')
                    ->get(),

                'new_booking_request' => Event::where('user_id', $user->id)
                    ->whereDate('date', $today)
                    ->whereDate('created_at', $today)
                    ->latest()
                    ->take(3)
                    ->get(),

                'upcoming_request' => Event::where('user_id', $user->id)
                    ->whereDate('date', '>', $today)
                    ->orderBy('date')
                    ->take(5)
                    ->get(),
            ];
        });

        return $this->sendResponse(
            new HomeResource($data),
            __('Home Data Retrieved Successfully (Cached)')
        );
    }
}
