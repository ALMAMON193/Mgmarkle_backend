<?php

namespace App\Http\Resources\SpiritualGuide;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StoreResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        // Convert date safely
        $date = Carbon::parse($this->date);

        return [
            'id' => $this->id,
            'user' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? null,
                'email' => $this->user->email ?? null,
            ],
            'title' => $this->title,
            'category' => $this->category,

            // ✅ Separate day and date
            'day' => $date->format('l'),         // e.g. Monday
            'date' => $date->format('Y-m-d'),    // e.g. 2025-10-20

            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'description' => $this->description,
            'visibility' => $this->visibility,
            'image_url' => $this->image ? Helper::generateURL($this->image) : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
        ];
    }
}
