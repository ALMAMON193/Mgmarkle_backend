<?php

namespace App\Http\Resources\SpiritualGuide;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->date);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'category' => $this->category,
            'day' => $date->format('l'),
            'date' => $date->format('Y-m-d'),
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
            'location' => $this->location,
            'visibility' => $this->visibility,
            'is_popular' => $this->is_popular,
            'zoom_session_id' => $this->zoom_session_id,
            'image_url' => $this->image ? Helper::generateURL($this->image) : null,
        ];
    }
}
