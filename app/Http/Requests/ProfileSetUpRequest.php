<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileSetUpRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Set to true if all authenticated users can access
    }

    public function rules()
    {
        return [
            'birth_date' => 'required|date',
            'gender' => 'required|in:male,female,others',
            'about_us' => 'required|string',
            'profile_picture' => 'required|image|max:20048', // max 2MB
            'affiliated_offer' => 'nullable|string|max:255',
            'topic_offer' => 'required|array',
            'topic_offer.*' => 'string|max:255',
            'category_id' => 'nullable|exists:categories,id',
            'sub_category_id' => 'nullable|exists:sub_categories,id',
        ];
    }
}
