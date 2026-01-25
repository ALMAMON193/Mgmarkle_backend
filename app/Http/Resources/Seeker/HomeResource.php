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
            'popular_leaders' => $this->formatLeaders($this->popular_leaders ?? []),
            'upcoming_event' => $this->formatBasicEvents($this->upcoming_event ?? []),
        ];
    }

    // For Leaders (User objects)
    private function formatLeaders($users): array
    {
        return collect($users)->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'user_type' => $user->user_type,
                'session_price' => $user->session_price,
                'avatar' => $user->profile?->profile_picture ? asset($user->profile->profile_picture) : null,
                'about' => $user->profile?->about_us,
                'rating' => $user->ratings_avg_rating ? round($user->ratings_avg_rating, 1) : 0,
            ];
        })->toArray();
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

    private function formatDate($date): ?string
    {
        return $date ? Carbon::parse($date)->format('d F, Y') : null;
    }

    private function formatTime($time): ?string
    {
        return $time ? Carbon::parse($time)->format('g A') : null;
    }
}
