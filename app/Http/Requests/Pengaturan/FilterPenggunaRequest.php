<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi filter daftar pengguna pada halaman Pengaturan Admin.
 *
 * Request ini hanya menerima pencarian singkat dan dua role resmi agar query
 * daftar pengguna tidak menerima parameter filter yang tidak dikenal.
 */
class FilterPenggunaRequest extends FormRequest
{
    /**
     * Memastikan hanya Admin yang dapat memakai filter pengguna.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Menentukan aturan validasi filter pengguna.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'role' => ['nullable', 'in:admin,operator'],
        ];
    }

    /**
     * Membersihkan spasi di awal dan akhir pencarian sebelum validasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => is_string($this->input('q')) ? trim($this->input('q')) : $this->input('q'),
        ]);
    }

    /**
     * Menentukan nama atribut filter dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'q' => 'pencarian pengguna',
            'role' => 'role pengguna',
        ];
    }
}
