<?php

namespace App\Http\Resources\Seeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventBookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $event = $this->event;
        $eventDate = $event ? \Carbon\Carbon::parse($event->date) : null;
        $startTime = $event ? \Carbon\Carbon::parse($event->start_time) : null;
        $endTime = $event ? \Carbon\Carbon::parse($event->end_time) : null;

        return [
            'id' => $this->id,
            'status' => $this->status,
            'booking_type' => $this->booking_type,
            'joined_at' => $this->created_at->format('d M, Y h:i A'),
            'event' => [
                'id' => $event->id ?? null,
                'title' => $event->title ?? null,
                'date' => $eventDate ? $eventDate->format('d F, Y') : null,
                'time' => ($startTime && $endTime) ? $startTime->format('g:i A').' - '.$endTime->format('g:i A') : null,
                'location' => $event->location ?? null,
                'image' => ($event && $event->image) ? \App\Helpers\Helper::generateURL($event->image) : null,
                'organizer' => [
                    'name' => $event->user->name ?? null,
                    'avatar' => ($event && $event->user->profile && $event->user->profile->profile_picture)
                        ? \App\Helpers\Helper::generateURL($event->user->profile->profile_picture)
                        : null,
                ],
            ],
        ];
    }
}
