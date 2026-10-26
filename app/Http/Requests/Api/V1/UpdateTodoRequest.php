<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTodoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|nullable|string',
            'is_completed' => 'sometimes|boolean',
            'scheduled_start_at' => 'sometimes|nullable|date',
            'scheduled_end_at' => 'sometimes|nullable|date|after:scheduled_start_at',
            'due_at' => 'sometimes|nullable|date',
            'priority' => 'sometimes|in:low,medium,high',
        ];
    }
}