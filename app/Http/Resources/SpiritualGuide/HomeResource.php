<?php

namespace App\Http\Resources\SpiritualGuide;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'today_schedule' => HomeScheduleResource::collection($this['today_schedule']),
            'new_booking_request' => HomeBookingResource::collection($this['new_booking_request']),
            'upcoming_event' => HomeEventResource::collection($this['upcoming_request']),
        ];
    }
}
