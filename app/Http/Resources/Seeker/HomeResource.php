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
    private function formatLeaders($items): array
    {
        // Wrap single model in a collection
        if ($items instanceof \Illuminate\Database\Eloquent\Model) {
            $items = collect([$items]);
        } else {
            $items = collect($items);
        }

        return $items->map(function ($user) {
            if (! is_object($user) || ! isset($user->id)) {
                return null;
            }

            return [
                'id' => $user->id,
                'name' => $user->name,
                'user_type' => $user->user_type,
                'session_price' => $user->session_price,
                'avatar' => $user->profile?->profile_picture
                    ? \App\Helpers\Helper::generateURL($user->profile->profile_picture)
                    : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&color=7F9CF5&background=EBF4FF',
                'about' => $user->profile?->about_us ?? 'No description available.',
                'rating' => $user->ratings_avg_rating ? round($user->ratings_avg_rating, 1) : 0,
                'is_online' => $user->is_online,
            ];
        })->filter()->values()->toArray();
    }

    // For upcoming_schedule and upcoming_event (matches premium design card)
    private function formatBasicEvents($items): array
    {
        // Wrap single model in a collection
        if ($items instanceof \Illuminate\Database\Eloquent\Model) {
            $items = collect([$items]);
        } else {
            $items = collect($items);
        }

        return $items->map(function ($item) {
            // If it's an EventBooking, we need to get the related Event
            $event = ($item instanceof \App\Models\EventBooking) ? $item->event : $item;

            // If it's still null or not an object with an ID, skip it
            if (! is_object($event) || ! isset($event->id)) {
                return null;
            }

            $date = Carbon::parse($event->date);
            $startTime = Carbon::parse($event->start_time);
            $endTime = Carbon::parse($event->end_time);

            return [
                'id' => $event->id,
                'title' => $event->title,
                'location' => $event->location,
                'date' => $date->format('d M, l'),
                'time' => $startTime->format('g a').' - '.$endTime->format('g a'),
                'image' => $event->image ? \App\Helpers\Helper::generateURL($event->image) : null,
                'zoom_session_id' => $event->zoom_session_id,
                'organizer' => [
                    'id' => $event->user->id,
                    'name' => $event->user->name ?? 'Organizer',
                    'avatar' => $event->user->profile?->profile_picture
                        ? \App\Helpers\Helper::generateURL($event->user->profile->profile_picture)
                        : 'https://ui-avatars.com/api/?name='.urlencode($event->user->name ?? 'User').'&color=7F9CF5&background=EBF4FF',
                ],
            ];
        })->filter()->values()->toArray();
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
