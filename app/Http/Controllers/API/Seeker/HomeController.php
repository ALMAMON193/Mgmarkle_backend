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
        $today = now()->toDateString();

        // Upcoming schedules
        $upcomingSchedule = Event::where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
            ->get();

        // Popular events by average rating
        $popularLeaders = User::whereHas('ratings', function ($query) {
            $query->where('rating', '>=', 4); // only consider good ratings
        })
            ->withCount('ratings') // total number of ratings
            ->withAvg('ratings', 'rating') // average rating
            ->orderByDesc('ratings_avg_rating') // sort by average rating first
            ->orderByDesc('ratings_count') // then by number of ratings
            ->get(); // fetch all users
        // Upcoming events
        $upcomingEvent = Event::where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
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
