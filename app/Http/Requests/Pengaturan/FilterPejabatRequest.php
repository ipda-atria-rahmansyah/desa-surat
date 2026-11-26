<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi parameter GET halaman pejabat penandatangan.
 *
 * Request ini membatasi filter pada nama dan dua status resmi agar query
 * daftar pejabat tetap terkontrol dan hanya dapat dipakai Admin.
 */
class FilterPejabatRequest extends FormRequest
{
    /**
     * Hanya Admin yang boleh membaca data pejabat penandatangan.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Menentukan aturan validasi filter pencarian pejabat.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'status' => ['nullable', 'in:aktif,nonaktif'],
        ];
    }

    /**
     * Membersihkan spasi pencarian sebelum nilai divalidasi.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => is_string($this->input('q')) ? trim($this->input('q')) : $this->input('q'),
        ]);
    }

    /**
     * Menentukan nama field yang ramah bagi pengguna.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'q' => 'pencarian nama pejabat',
            'status' => 'status pejabat',
        ];
    }

    /**
     * Menentukan pesan validasi filter dalam bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.max' => ':attribute maksimal :max karakter.',
            'status.in' => ':attribute tidak valid.',
        ];
    }
}
