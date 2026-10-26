<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreReminderRequest extends FormRequest
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
            'reminder_at' => 'required|date',
            'recurring' => 'sometimes|boolean',
            'recurring_pattern' => 'sometimes|nullable|in:daily,weekdays,weekly,before_event',
            'delivery_status' => 'sometimes|in:pending,sent,failed,delivered',
            'dedupe_key' => 'sometimes|nullable|string|unique:reminders,dedupe_key',
        ];
    }
}