<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'string', 'max:50'],
            'password' => ['required', 'string'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(['username' => trim((string) $this->input('username'))]);
    }

    public function attributes(): array
    {
        return [
            'username' => 'Nama pengguna',
            'password' => 'Kata sandi',
        ];
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
        ];
    }
}
