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
            'booking_status' => $this->booking_status,
            'joined_at' => $this->created_at->format('d M, Y h:i A'),
            'starts_at' => $this->starts_at?->format('d F, Y h:i A'),
            'ends_at' => $this->ends_at?->format('d F, Y h:i A'),
            'event' => [
                'id' => $event->id ?? null,
                'title' => $event->title ?? null,
                'date' => $this->starts_at ? $this->starts_at->format('d F, Y') : ($eventDate ? $eventDate->format('d F, Y') : null),
                'time' => $this->starts_at ? $this->starts_at->format('g:i A').' - '.$this->ends_at->format('g:i A') : (($startTime && $endTime) ? $startTime->format('g:i A').' - '.$endTime->format('g:i A') : null),
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
