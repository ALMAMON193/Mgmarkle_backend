<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\EventBookingResource;
use App\Http\Resources\Seeker\EventResource;
use App\Models\Event;
use App\Models\EventBooking;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class EventController extends Controller
{
    use ApiResponse;

    public function show($id)
    {
        $event = Event::with(['user.profile', 'bookings.user.profile'])->find($id);

        if (! $event) {
            return $this->sendError('Event Not Found', [], 404);
        }

        return $this->sendResponse(
            new EventResource($event),
            'Event Details Retrieved Successfully'
        );
    }

    // join event
    public function joinEvent(Request $request, $id)
    {
        $user = $request->user();

        if (! $user->is_subscribed) {
            return $this->sendError('You need a subscription to join this event.', [], 403);
        }

        $event = Event::find($id);

        if (! $event) {
            return $this->sendError('Event Not Found', [], 404);
        }

        // Check if already joined
        $existingBooking = EventBooking::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existingBooking) {
            return $this->sendError('You have already joined this event.', [], 409);
        }

        // Prepare timestamps from event date and times
        $eventDate = $event->date->format('Y-m-d');
        $startsAt = \Carbon\Carbon::parse($eventDate.' '.$event->start_time);
        $endsAt = \Carbon\Carbon::parse($eventDate.' '.$event->end_time);

        $booking = EventBooking::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'booking_type' => 'subscription',
            'amount' => 0.00,
            'status' => 'paid',
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
        ]);

        return $this->sendResponse(
            new EventBookingResource($booking),
            'Event Joined Successfully'
        );
    }
}
