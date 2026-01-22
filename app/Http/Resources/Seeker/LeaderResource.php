<?php

namespace App\Http\Resources\Seeker;

use App\Helpers\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LeaderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Calculate sessions
        $sessionCount = $this->events()->withCount('bookings')->get()->sum('bookings_count');
        
        // Mock data
        $experience = "2+";
        $price = "$40/ 1 hours";

        // Format working time
        $workingTime = "Not Available";
        $availability = $this->availableSlots()->first();
        if ($availability) {
            if ($availability->day && $availability->start_time && $availability->end_time) {
                 $workingTime = $availability->day . ', ' . $availability->start_time . ' - ' . $availability->end_time;
            } else {
                 $workingTime = "Monday - Friday, 08.00 AM - 20.00 PM";
            }
        }

        return [
            'id' => $this->id,
            'name' => $this->name,
            'affiliation' => $this->profile->school_name ?? 'Grace Fellowship',
            'avatar' => $this->profile && $this->profile->profile_picture 
                ? Helper::generateURL($this->profile->profile_picture) 
                : null,
            'is_available' => true,

            // Stats
            'price' => $price,
            'session_count' => $sessionCount . '+',
            'experience' => $experience,
            'rating' => $this->averageRating(),
            'total_ratings' => $this->ratings()->count(),

            // About
            'about_me' => $this->profile->about_us ?? '',

            // Working Time
            'working_time' => $workingTime,

            // Reviews
            'reviews' => $this->ratings()->with('user.profile')->take(3)->get()->map(function ($rating) {
                return [
                    'id' => $rating->id,
                    'user_name' => $rating->user->name,
                    'user_avatar' => $rating->user->profile && $rating->user->profile->profile_picture 
                        ? Helper::generateURL($rating->user->profile->profile_picture) 
                        : null,
                    'rating' => (float) $rating->rating,
                    'comment' => $rating->comment,
                    'created_at' => $rating->created_at->diffForHumans(),
                ];
            }),
        ];
    }
}
