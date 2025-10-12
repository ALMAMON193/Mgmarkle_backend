<?php

namespace App\Http\Resources\Seeker;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Map each event collection
        $mapEvent = fn ($events) => $events->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'date' => $event->date,
                'start_time' => $event->start_time,
                'end_time' => $event->end_time,
                'location' => $event->location,
                'visibility' => $event->visibility,
                'image' => $event->image,
                'average_rating' => $event->ratings->avg('rating') ?? 0,
                'total_ratings' => $event->ratings->count(),
            ];
        });

        return [
            'upcoming_schedule' => $mapEvent($this->upcoming_schedule),
            'popular_event' => $mapEvent($this->popular_event),
            'upcoming_event' => $mapEvent($this->upcoming_event),
        ];
    }
}
