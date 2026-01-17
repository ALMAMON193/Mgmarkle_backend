<?php

namespace App\Http\Requests\Journey;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name_of_trip' => 'required|string|max:255',
            'country_name' => 'nullable|array',
            'city_name' => 'nullable|array',
            'pick_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:pick_date',
            'who_coming_journey' => 'nullable|array',
            'why_where' => 'nullable|string',
            'trip_about' => 'nullable|string',
            'where_most_excited' => 'nullable|string|max:255',
            'description_manual' => 'nullable|string',
            'images.*' => 'nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ];
    }
}
