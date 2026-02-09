<?php

namespace App\Http\Resources\SpiritualGuide;

use App\Helpers\Helper;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HomeEventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->date);

        return [
            'id' => $this->id,
            'title' => $this->title,
            'location' => $this->location,
            'day' => $date->format('l'),
            'time_range' => Carbon::parse($this->start_time)->format('h:i A').' - '.Carbon::parse($this->end_time)->format('h:i A'),
            'image_url' => $this->image ? Helper::generateURL($this->image) : '',
        ];
    }
}
