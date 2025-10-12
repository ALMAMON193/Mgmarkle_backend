<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\HomeResource;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $today = now()->toDateString();

        // Upcoming schedules (next 5 upcoming events)
        $upcomingSchedule = Event::where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
            ->with('ratings') // eager load ratings
            ->get();

        // Popular events (top 5 by number of ratings)
        $popularEvent = Event::withCount('ratings')
            ->orderBy('ratings_count', 'desc')
            ->take(5)
            ->with('ratings')
            ->get();

        // Upcoming events (next 5 upcoming events)
        $upcomingEvent = Event::where('date', '>=', $today)
            ->orderBy('date', 'asc')
            ->take(5)
            ->with('ratings')
            ->get();

        // Wrap in object to avoid array property error
        $data = (object) [
            'upcoming_schedule' => $upcomingSchedule,
            'popular_event' => $popularEvent,
            'upcoming_event' => $upcomingEvent,
        ];

        return $this->sendResponse(
            new HomeResource($data),
            'Seeker home data retrieved successfully.'
        );
    }
}
