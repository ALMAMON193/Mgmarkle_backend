<?php

namespace App\Http\Resources\Seeker;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'upcoming_schedule' => $this->formatBasicEvents($this->upcoming_schedule ?? []),
            'popular_leaders' => $this->formatPopularEvents($this->popular_event ?? []),
            'upcoming_event' => $this->formatBasicEvents($this->upcoming_event ?? []),
        ];
    }

    // For upcoming_schedule and upcoming_event (no ratings)
    private function formatBasicEvents($events): array
    {
        return collect($events)->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'date' => $this->formatDate($event->date),
                'start_time' => $this->formatTime($event->start_time),
                'end_time' => $this->formatTime($event->end_time),
            ];
        })->toArray();
    }

    // For popular_event (with ratings)
    private function formatPopularEvents($events): array
    {
        return collect($events)->map(function ($event) {
            return [
                'id' => $event->id,
                'title' => $event->title,
                'category' => $event->category,
                'date' => $this->formatDate($event->date),
                'start_time' => $this->formatTime($event->start_time),
                'end_time' => $this->formatTime($event->end_time),
                'ratings_count' => $event->ratings_count ?? 0,
                'ratings' => $event->ratings->map(fn ($r) => [
                    'id' => $r->id,
                    'rating' => $r->rating,
                    'comment' => $r->comment,
                ]),
            ];
        })->toArray();
    }

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d F, Y') : null;
    }

    private function formatTime($time): ?string
    {
        return $time ? Carbon::parse($time)->format('g A') : null;
    }
}
