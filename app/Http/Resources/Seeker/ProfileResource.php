<?php

namespace App\Http\Resources\Seeker;

use App\Helpers\Helper;
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
            'birth_date' => $this->profile?->birth_date ? \Carbon\Carbon::parse($this->profile->birth_date)->format('d/m/Y') : null,
            'gender' => $this->profile?->gender ?? null,
            'about_us' => $this->profile?->about_us ?? null,
            'profile_picture' => $this->profile?->profile_picture ? Helper::generateURL($this->profile->profile_picture) : null,
            'is_subscribed' => $this->is_subscribed,
        ];
    }
}
