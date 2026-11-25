<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi pembuatan akun pengguna baru oleh Admin.
 *
 * Password diproses oleh cast hashed pada model User setelah data tervalidasi.
 */
class StorePenggunaRequest extends FormRequest
{
    /**
     * Hanya Admin yang boleh membuat akun pengguna.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Menentukan aturan akun pengguna baru.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'username' => ['required', 'alpha_dash', 'min:4', 'max:50', 'unique:tbmaster_users,username'],
            'role' => ['required', 'in:admin,operator'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ];
    }

    /**
     * Merapikan nama dan username sebelum validasi agar data konsisten.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'name' => is_string($this->input('name')) ? trim($this->input('name')) : $this->input('name'),
            'username' => is_string($this->input('username')) ? trim($this->input('username')) : $this->input('username'),
        ]);
    }

    /**
     * Menentukan label validasi dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'Nama',
            'username' => 'Username',
            'role' => 'Role',
            'password' => 'Kata sandi',
            'password_confirmation' => 'Konfirmasi kata sandi',
        ];
    }

    /**
     * Menentukan pesan validasi ramah untuk form Admin.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'alpha_dash' => ':attribute hanya boleh berisi huruf, angka, tanda hubung, dan garis bawah.',
            'unique' => ':attribute sudah terdaftar.',
            'confirmed' => 'Konfirmasi kata sandi tidak cocok.',
            'min' => ':attribute minimal :min karakter.',
            'max' => ':attribute maksimal :max karakter.',
            'in' => ':attribute tidak valid.',
        ];
    }
}