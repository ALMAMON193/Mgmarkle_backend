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
        $user = auth()->user();

        // 1️⃣ Validate input
        $validated = $request->validate([
            'leader_id' => 'required|exists:users,id',
            'rating' => 'required|numeric|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // 2️⃣ Check if the target is a spiritual guide
        $leader = \App\Models\User::find($validated['leader_id']);
        if ($leader->user_type !== 'spiritual_guide') {
            return $this->sendError('You can only rate Spiritual Guides.', [], 422);
        }

        // 3️⃣ Prevent self-rating
        if ($user->id == $leader->id) {
            return $this->sendError('You cannot rate yourself.', [], 422);
        }

        // 4️⃣ Find existing rating or create new one
        $searchData = [
            'user_id' => $user->id,
            'leader_id' => $validated['leader_id'],
        ];

        $rating = Rating::updateOrCreate(
            $searchData,
            [
                'rating' => $validated['rating'],
                'comment' => $validated['comment'] ?? null,
            ]
        );

        // 5️⃣ Return response
        return $this->sendResponse(
            $rating,
            $rating->wasRecentlyCreated
                ? 'Rating submitted successfully.'
                : 'Rating updated successfully.'
        );
    }
}
