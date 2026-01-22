<?php

namespace App\Http\Controllers\API\SpiritualGuide;

use App\Http\Controllers\Controller;
use App\Http\Resources\SpiritualGuide\HomeResource;
use App\Models\Event;
use App\Traits\ApiResponse;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeApiController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today()->toDateString();

        $data = [
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

        return $this->sendResponse(
            new HomeResource($data),
            __('Home Data Retrieved Successfully')
        );
    }
}
