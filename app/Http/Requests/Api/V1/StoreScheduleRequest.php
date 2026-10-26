<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreScheduleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after:start_time',
            'location' => 'nullable|string|max:255',
            'is_all_day' => 'sometimes|boolean',
            'recurring' => 'sometimes|boolean',
            'recurring_pattern' => 'sometimes|nullable|in:daily,weekdays,weekly,monthly',
            'timezone' => 'sometimes|string|in:Asia/Jakarta,UTC,Asia/Bangkok,Asia/Kolkata',
            'is_overnight' => 'sometimes|boolean',
        ];
    }
}