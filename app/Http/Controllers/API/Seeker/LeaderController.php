<?php

namespace App\Http\Controllers\API\Seeker;

use App\Http\Controllers\Controller;
use App\Http\Resources\Seeker\LeaderResource;
use App\Models\User;
use App\Traits\ApiResponse;

class LeaderController extends Controller
{
    use ApiResponse;

    public function show($id)
    {
        $leader = User::where('user_type', 'spiritual_guide')
            ->with(['profile', 'ratings.user.profile', 'availableSlots', 'events.bookings'])
            ->find($id);

        if (! $leader) {
            return $this->sendError('Leader Not Found');
        }

        return $this->sendResponse(
            new LeaderResource($leader),
            'Leader Details Retrieved Successfully'
        );
    }
}
