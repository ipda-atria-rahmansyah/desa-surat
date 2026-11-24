<?php

namespace App\Http\Requests\Arsip;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi parameter pencarian dan rentang tanggal halaman arsip.
 *
 * Request ini memastikan query arsip hanya menerima field filter yang
 * didukung dan menjaga pesan validasi tetap ramah bagi operator desa.
 */
class FilterArsipRequest extends FormRequest
{
    /**
     * Semua pengguna terautentikasi boleh melihat dan memfilter arsip.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Membersihkan spasi pada input teks sebelum aturan diterapkan.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'q' => is_string($this->q) ? trim($this->q) : $this->q,
            'tanggal_mulai' => is_string($this->tanggal_mulai) ? trim($this->tanggal_mulai) : $this->tanggal_mulai,
            'tanggal_selesai' => is_string($this->tanggal_selesai) ? trim($this->tanggal_selesai) : $this->tanggal_selesai,
        ]);
    }

    /**
     * Aturan filter arsip sesuai validator project.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'q' => ['nullable', 'string', 'max:100'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ];
    }

    /**
     * Menyediakan pesan validasi Bahasa Indonesia untuk toolbar arsip.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'q.string' => 'Pencarian tidak valid.',
            'q.max' => 'Pencarian maksimal 100 karakter.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }

    /**
     * Menampilkan nama field yang mudah dipahami pada pesan validasi.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'q' => 'Pencarian',
            'tanggal_mulai' => 'Tanggal mulai',
            'tanggal_selesai' => 'Tanggal selesai',
        ];
    }
}
