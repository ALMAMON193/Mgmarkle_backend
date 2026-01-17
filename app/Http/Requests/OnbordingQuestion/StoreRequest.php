<?php

namespace App\Http\Requests\OnbordingQuestion;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id'           => 'required|exists:users,id',
            'type_of_memries'   => 'required|string|max:255',
            'planing_to_jurnal' => 'required|string|max:255',
            'out_of_jurnung'    => 'required|string|max:255',

            // Reminder fields (optional)
            'reminders'                 => 'array|nullable',
            'reminders.*.type'          => 'required_with:reminders|in:morning,evening',
            'reminders.*.reminder_time' => 'required|date_format:H:i',
            'reminders.*.is_enabled'    => 'boolean',
        ];
    }
}
