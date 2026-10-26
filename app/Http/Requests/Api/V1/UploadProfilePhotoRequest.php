<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UploadProfilePhotoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'photo.required' => 'Profile photo file is required.',
            'photo.image' => 'File must be a valid image.',
            'photo.mimes' => 'Image must be JPEG, PNG, JPG, GIF, or WebP.',
            'photo.max' => 'Image must not exceed 2 MB.',
        ];
    }
}