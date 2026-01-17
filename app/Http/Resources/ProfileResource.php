<?php

namespace App\Http\Resources;

use App\Helpers\Helper;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender,
            'about_us' => $this->about_us,
            'profile_picture' => $this->profile_picture ? Helper::generateURL($this->profile_picture) : '',
            'affiliated_offer' => $this->affiliated_offer,
            'topic_offer' => is_string($this->topic_offer) ? json_decode($this->topic_offer) : $this->topic_offer,
            'category_name' => $this->category ? $this->category->name : null,
            'sub_categories' => $this->subCategories->map(function ($sub) {
                return [
                    'id' => $sub->id,
                    'name' => $sub->name,
                ];
            }),
        ];
    }
}
