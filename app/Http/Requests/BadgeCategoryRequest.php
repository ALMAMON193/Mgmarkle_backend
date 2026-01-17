<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BadgeCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Policy check
    }

    public function rules(): array
    {
        $id = $this->categoryId ?? null;

        return [
            'name' => 'required|string|max:255',
            'badge_code' => 'required|string|max:50|unique:badge_categories,badge_code,'.$id,
            'icon' => 'nullable|image|max:2048',
            'threshold' => 'nullable|numeric',
            'reward_points' => 'required|numeric|min:0',
            'unlock_toast' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ];
    }
}
