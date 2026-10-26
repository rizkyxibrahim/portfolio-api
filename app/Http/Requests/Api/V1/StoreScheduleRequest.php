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
            'recurring' => 'sometimes|nullable|boolean',
            'recurring_pattern' => 'sometimes|nullable|in:daily,weekdays,weekly,monthly',
            'timezone' => 'sometimes|string|in:Asia/Jakarta,UTC,Asia/Bangkok,Asia/Kolkata',
            'is_overnight' => 'sometimes|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'Schedule title is required.',
                        'start_time.required' => 'Start time is required.',
                        'end_time.after' => 'End time must be after start time.',
                        'recurring.boolean' => 'Recurring must be true or false.',
                        'timezone.in' => 'Invalid timezone.',
        ];
    }

    /**
         * Set default recurring to false if null.
         */
    protected function prepareForValidation(): void
    {
        if ($this->input('recurring') === null) {
            $this->merge(['recurring' => false]);
        }
    }
}
