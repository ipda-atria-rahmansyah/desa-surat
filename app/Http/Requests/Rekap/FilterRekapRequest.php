<?php

namespace App\Http\Requests\Rekap;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi filter kategori dan periode pada halaman Rekapitulasi Surat.
 *
 * Request ini membatasi kategori pada empat kategori resmi sistem dan
 * memastikan rentang tanggal dapat diproses aman oleh controller.
 */
class FilterRekapRequest extends FormRequest
{
    /**
     * Semua pengguna yang sudah login boleh melihat rekapitulasi.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Merapikan nilai filter sebelum validasi agar spasi tidak mengubah hasil.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'kategori' => is_string($this->kategori) ? trim($this->kategori) : $this->kategori,
            'tanggal_mulai' => is_string($this->tanggal_mulai) ? trim($this->tanggal_mulai) : $this->tanggal_mulai,
            'tanggal_selesai' => is_string($this->tanggal_selesai) ? trim($this->tanggal_selesai) : $this->tanggal_selesai,
        ]);
    }

    /**
     * Aturan filter sesuai empat kategori resmi dan validator project.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'kategori' => ['nullable', 'in:kematian,kelahiran,keterangan_umum,pindah'],
            'tanggal_mulai' => ['nullable', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
        ];
    }

    /**
     * Menyediakan pesan validasi yang mudah dipahami operator desa.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'kategori.in' => 'Kategori surat tidak valid.',
            'tanggal_mulai.date' => 'Tanggal mulai tidak valid.',
            'tanggal_selesai.date' => 'Tanggal selesai tidak valid.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai harus sama atau setelah tanggal mulai.',
        ];
    }

    /**
     * Mengubah nama field teknis menjadi label Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'kategori' => 'Kategori',
            'tanggal_mulai' => 'Tanggal mulai',
            'tanggal_selesai' => 'Tanggal selesai',
        ];
    }
}
