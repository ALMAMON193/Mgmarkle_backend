<?php

namespace App\Http\Resources\Seeker;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Format date: "18 August, 2025" and time: "Tuesday, 4:00PM - 9:00PM"
        $date = Carbon::parse($this->date);
        $startTime = Carbon::parse($this->start_time);
        $endTime = Carbon::parse($this->end_time);

        // Check if current user has joined/booked
        $isJoined = false;
        if (auth()->check()) {
            $isJoined = $this->bookings()->where('user_id', auth()->id())->exists();
        }

        // Get joined users avatars (limit 3)
        $joinedUsers = $this->bookings()
            ->with(['user.profile'])
            ->take(3)
            ->get()
            ->map(function ($booking) {
                // Safe check for user and profile
                if ($booking->user && $booking->user->profile && $booking->user->profile->profile_picture) {
                    return Helper::generateURL($booking->user->profile->profile_picture);
                }

                // Fallback to initial-based avatar
                $name = $booking->user->name ?? 'User';

                return 'https://ui-avatars.com/api/?name=' . urlencode($name) . '&color=7F9CF5&background=EBF4FF';
            });

        $user = auth('sanctum')->user() ?? auth()->user();
        $userIsSubscribed = $user ? (bool) $user->is_subscribed : false;
        $userCredits = $user ? (int) ($user->available_credits ?? 0) : 0;
        $canJoin = $userIsSubscribed || ($userCredits > 0);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'date_formatted' => $date->format('d F, Y'),
            'time_formatted' => $date->format('l') . ', ' . $startTime->format('g:iA') . ' - ' . $endTime->format('g:iA'),
            'location' => $this->location,
            'description' => $this->description,
            'session_id' => $this->zoom_session_id,
            'image' => $this->image ? Helper::generateURL($this->image) : null,

            // Organizer Details
            'organizer' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'role' => 'Organizer',
                'category' => $this->user->profile?->category?->name,
                'avatar' => ($this->user->profile && $this->user->profile->profile_picture)
                    ? Helper::generateURL($this->user->profile->profile_picture)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($this->user->name) . '&color=7F9CF5&background=EBF4FF',
            ],

            // Join & Subscription Status
            'joined_count'           => $this->bookings()->count(),
            'joined_users'           => $joinedUsers->values(),
            'is_already_joined'      => $isJoined,
            'has_event_access'       => $canJoin,
            'user_is_subscribed'     => $userIsSubscribed,
            'user_available_credits' => $userCredits,
        ];
    }
}
