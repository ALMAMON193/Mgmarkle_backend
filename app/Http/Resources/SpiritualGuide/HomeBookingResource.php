<?php

namespace App\Http\Resources\SpiritualGuide;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeBookingResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $startsAt = $this->starts_at ? Carbon::parse($this->starts_at) : null;
        
        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'avatar' => ($this->user && $this->user->profile && $this->user->profile->profile_picture)
                    ? Helper::generateURL($this->user->profile->profile_picture)
                    : null,
            ],
            'status_text' => $startsAt 
                ? $startsAt->format('l, h:i A') . ' - ' . ($this->event->title ?? 'Counseling')
                : 'Counseling',
        ];
    }
}
