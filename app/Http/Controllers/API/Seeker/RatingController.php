<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Models\Rating;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    use ApiResponse;

    public function store(Request $request)
    {
        $user = $request->user();

        // 1️⃣ Validate input
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'rating' => 'required|numeric|min:0|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 2️⃣ Find existing rating
        $rating = Rating::firstOrNew([
            'event_id' => $validated['event_id'],
            'user_id' => $user->id,
        ]);

        // 3️⃣ Update rating fields
        $rating->rating = $validated['rating'];
        $rating->comment = $validated['comment'] ?? $rating->comment;
        $rating->save();

        // 4️⃣ Return response
        return $this->sendResponse(
            $rating, // data
            $rating->wasRecentlyCreated
                ? 'Event rated successfully.'
                : 'Event rating updated successfully.'
        );
    }
}
