<?php

namespace App\Http\Controllers\API\SpiritualGuide\Profile;

use App\Http\Controllers\Controller;
use App\Http\Resources\AvailableSlotsResponse;
use App\Models\Availability;
use App\Traits\ApiResponse;

class ProfileApiController extends Controller
{
    use ApiResponse;

    public function profileDetails() {}

    // available slots for spiritual guide
    public function availableSlots()
    {
        $slots = Availability::where('user_id', auth()->user()->id)
            ->where('status', 'available')
            ->orderBy('created_at', 'desc')
            ->orderBy('date')
            ->orderBy('start_time')
            ->get();

        return $this->sendResponse(
            AvailableSlotsResponse::collection($slots),
            'Available slots retrieved successfully.'
        );
    }
}
