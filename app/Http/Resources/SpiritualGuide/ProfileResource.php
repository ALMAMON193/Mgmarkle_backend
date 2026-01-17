<?php

namespace App\Http\Resources\SpiritualGuide;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,

            'profile' => $this->whenLoaded('profile', [
                'id' => $this->profile?->id ?? null,
                'birth_date' => $this->profile?->birth_date ?? null,
                'gender' => $this->profile?->gender ?? null,
                'about_us' => $this->profile?->about_us ?? null,
                'profile_picture' => $this->profile?->profile_picture ?? null,
                'affiliated_offer' => $this->profile?->affiliated_offer ?? null,
                'topic_offer' => $this->profile?->topic_offer ?? [],
                'category_name' => $this->profile?->category?->name ?? null,
                'sub_category_name' => $this->profile?->subCategory?->name ?? null,
            ]) ?? [],
            'available_slots' => $this->whenLoaded('availableSlots', $this->availableSlots) ?? [],
        ];
    }
}
