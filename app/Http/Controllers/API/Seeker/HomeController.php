<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\HomeResource;
use App\Models\Event;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $authUserId = auth()->id();
        $today = now()->toDateString();
        $upcomingSchedule = \App\Models\EventBooking::where('user_id', $authUserId)
            ->where('status', 'paid')
            ->where('starts_at', '>', now())
            ->with(['event', 'event.user', 'event.user.profile'])
            ->orderBy('starts_at', 'asc')
            ->first();

        $popularLeaders = User::where('user_type', 'spiritual_guide')
            ->with('profile')
            ->withAvg('ratings', 'rating')
            ->orderByRaw('ratings_avg_rating IS NULL ASC, ratings_avg_rating DESC')
            ->take(3)
            ->get();

        $upcomingEvent = Event::where('date', '>=', $today)
            ->where('visibility', 'public')
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->take(3)
            ->get();

        $data = (object) [
            'upcoming_schedule' => $upcomingSchedule,
            'popular_leaders' => $popularLeaders,
            'upcoming_event' => $upcomingEvent,
        ];

        return $this->sendResponse(
            new HomeResource($data),
            'Seeker home data retrieved successfully.'
        );
    }
}
