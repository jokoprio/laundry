<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class PasswordChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        $fail('Password saat ini tidak sesuai.');
                    }
                }
            ],
            'new_password' => ['required', 'string', 'min:8', 'confirmed'],
            'new_password_confirmation' => ['required', 'string'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'current_password.required' => 'Password saat ini harus diisi.',
            'new_password.required' => 'Password baru harus diisi.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'new_password_confirmation.required' => 'Konfirmasi password harus diisi.',
        ];
    }
}
