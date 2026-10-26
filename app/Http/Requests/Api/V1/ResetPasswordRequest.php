<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'token' => 'required|string',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'token.required' => 'Token reset diperlukan.',
            'email.required' => 'Email diperlukan.',
            'password.required' => 'Password baru diperlukan.',
            'password.min' => 'Password minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ];
    }
}
