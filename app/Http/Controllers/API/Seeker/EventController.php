<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\EventResource;
use App\Models\Event;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponse;

    public function show($id)
    {
        $event = Event::with(['user.profile', 'bookings.user.profile'])->find($id);

        if (!$event) {
            return $this->sendError('Event Not Found', [], 404);
        }

        return $this->sendResponse(
            new EventResource($event),
            'Event Details Retrieved Successfully'
        );
    }
}
