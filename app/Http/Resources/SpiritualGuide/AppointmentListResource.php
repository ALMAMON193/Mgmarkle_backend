<?php

namespace App\Http\Resources\SpiritualGuide;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $startsAt = $this->starts_at ? Carbon::parse($this->starts_at) : null;
        $endsAt = $this->ends_at ? Carbon::parse($this->ends_at) : null;

        return [
            'id' => $this->id,
            'user_name' => $this->user->name ?? null,
            'avatar' => ($this->user && $this->user->profile && $this->user->profile->profile_picture)
                ? Helper::generateURL($this->user->profile->profile_picture)
                : null,
            'fellowship' => $this->user->profile?->category?->name ?? 'Grace Fellowship',
            'time_text' => $startsAt ? $startsAt->format('h:i A') : '',
            'duration' => ($startsAt && $endsAt)
                ? $startsAt->format('h:i A').' - '.$endsAt->format('h:i A')
                : '',
            'date_text' => $startsAt ? $startsAt->format('d M, l') : '',
            'status' => $this->booking_status,
            'payment_status' => $this->status,
            'event_title' => $this->event->title ?? 'Session',
        ];
    }
}
