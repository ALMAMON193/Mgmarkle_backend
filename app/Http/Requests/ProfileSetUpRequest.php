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
            'profile_picture' => 'required|image|max:20048',
            'affiliated_offer' => 'nullable|string|max:255',
            'topic_offer' => 'required|array',
            'topic_offer.*' => 'string|max:255',
            'category_id' => $this->user()->user_type === 'spiritual_guide' ? 'required|exists:categories,id' : 'nullable|exists:categories,id',
            'sub_categories' => $this->user()->user_type === 'spiritual_guide' ? 'required|array|min:1' : 'nullable|array',
            'sub_categories.*' => 'exists:sub_categories,id',
        ];
    }
}
