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
            ->with('user.profile')
            ->take(3)
            ->get()
            ->map(function ($booking) {
                return $booking->user->profile->profile_picture
                    ? Helper::generateURL($booking->user->profile->profile_picture)
                    : null; // Or default avatar URL
            })
            ->filter(); // Remove nulls

        return [
            'id' => $this->id,
            'title' => $this->title,
            'date_formatted' => $date->format('d F, Y'),
            'time_formatted' => $date->format('l').', '.$startTime->format('g:iA').' - '.$endTime->format('g:iA'),
            'location' => $this->location,
            'description' => $this->description,
            'image' => $this->image ? Helper::generateURL($this->image) : null,

            // Organizer Details
            'organizer' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'role' => 'Organizer', // Or logic to determine role/title
                'avatar' => $this->user->profile && $this->user->profile->profile_picture
                    ? Helper::generateURL($this->user->profile->profile_picture)
                    : null,
            ],

            // Join Status
            'joined_count' => $this->bookings()->count(),
            'joined_users' => $joinedUsers->values(), // Reset keys
            'is_joined' => $isJoined,
        ];
    }
}
