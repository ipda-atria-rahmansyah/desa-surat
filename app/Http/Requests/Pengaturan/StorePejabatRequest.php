<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi data pejabat penandatangan baru yang dikirim Admin.
 *
 * Aturan hanya mengikuti kolom yang dapat diisi pada migration
 * `tbmaster_pejabat`; primary key dan timestamps tidak pernah diterima dari
 * frontend.
 */
class StorePejabatRequest extends FormRequest
{
    /**
     * Hanya Admin yang boleh menambahkan pejabat penandatangan.
     *
     * Middleware rute tetap menjadi perlindungan utama, sedangkan pemeriksaan
     * ini mencegah request dipakai di endpoint lain tanpa otorisasi.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Menentukan validasi dari tipe, panjang, dan nullable migration pejabat.
     *
     * `nip` berupa string varchar(30) nullable; angka saja diberlakukan sesuai
     * kebutuhan format NIP tanpa menambahkan unique karena migration tidak
     * memiliki constraint tersebut.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'nama_pejabat' => ['required', 'string', 'max:150'],
            'jabatan' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:30', 'regex:/^\d+$/'],
            'pangkat' => ['nullable', 'string', 'max:100'],
            'status_aktif' => ['boolean'],
        ];
    }

    /**
     * Merapikan teks, menghapus pemisah NIP, mengubah kosong menjadi null, dan
     * memastikan checkbox yang tidak dikirim tetap diterjemahkan sebagai false.
     *
     * Normalisasi dilakukan sebelum validasi agar nilai yang diuji dan disimpan
     * konsisten dengan aturan input form.
     */
    protected function prepareForValidation(): void
    {
        $nullableText = static function (mixed $value): ?string {
            if (! is_string($value)) {
                return $value === null ? null : (string) $value;
            }

            $value = trim($value);

            return $value === '' ? null : $value;
        };

        $nip = $nullableText($this->input('nip'));

        if ($nip !== null) {
            $nip = preg_replace('/[\s.\-]+/', '', $nip);
        }

        $this->merge([
            'nama_pejabat' => is_string($this->input('nama_pejabat')) ? trim($this->input('nama_pejabat')) : $this->input('nama_pejabat'),
            'jabatan' => is_string($this->input('jabatan')) ? trim($this->input('jabatan')) : $this->input('jabatan'),
            'nip' => $nip,
            'pangkat' => $nullableText($this->input('pangkat')),
            'status_aktif' => $this->has('status_aktif') ? $this->boolean('status_aktif') : false,
        ]);
    }

    /**
     * Menetapkan label field yang mudah dipahami pengguna.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_pejabat' => 'Nama Pejabat',
            'jabatan' => 'Jabatan',
            'nip' => 'NIP',
            'pangkat' => 'Pangkat',
            'status_aktif' => 'Status Aktif',
        ];
    }

    /**
     * Menyediakan pesan validasi singkat dalam Bahasa Indonesia.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
            'regex' => ':attribute harus berupa angka.',
            'boolean' => ':attribute tidak valid.',
        ];
    }
}
