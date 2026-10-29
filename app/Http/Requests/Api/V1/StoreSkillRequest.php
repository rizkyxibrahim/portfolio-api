<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'category' => 'nullable|string|max:50',
            'level' => 'sometimes|in:beginner,intermediate,advanced,expert',
            'years_of_experience' => 'nullable|integer|min:0',
        ];
    }
}
