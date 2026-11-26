<?php

namespace App\Http\Requests\Pengaturan;

use Illuminate\Foundation\Http\Attributes\ErrorBag;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Memvalidasi perubahan data desa oleh Admin melalui popup Pengaturan.
 *
 * Request ini hanya menerima kolom yang tersedia pada tabel `tbmaster_desa`.
 * Record desa yang diubah dipilih server oleh controller, bukan dari input form.
 */
#[ErrorBag('updateDesa')]
class UpdateDesaRequest extends FormRequest
{
    /** Error form ini dipisahkan agar modal hanya terbuka kembali untuk update desa. */
    protected $errorBag = 'updateDesa';

    /**
     * Memastikan hanya Admin yang boleh mengubah data desa.
     *
     * @return bool True jika pengguna yang sedang login memiliki role Admin.
     */
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    /**
     * Menentukan aturan berdasarkan panjang dan nullable pada migration desa.
     *
     * `alamat_kantor` bertipe text sehingga tidak diberi batas max buatan.
     * `kode_pos` disimpan sebagai string maksimal 10 karakter dan hanya menerima angka.
     *
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            // Kolom wilayah wajib mengikuti batas string pada migration.
            'nama_desa' => ['required', 'string', 'max:150'],
            'kecamatan' => ['required', 'string', 'max:100'],
            'kabupaten' => ['required', 'string', 'max:100'],
            'provinsi' => ['required', 'string', 'max:100'],

            // Kolom nullable menerima null setelah input kosong dibersihkan.
            'alamat_kantor' => ['nullable', 'string'],
            'kode_pos' => ['nullable', 'regex:/^\d{1,10}$/'],

            // Logo wajib berupa gambar lokal yang diizinkan dan dibatasi 2 MB.
            'logo' => ['nullable', 'image', 'mimes:png,jpg,jpeg', 'max:2048'],
        ];
    }

    /**
     * Membersihkan teks sebelum validasi tanpa mengubah UploadedFile.
     *
     * String kosong untuk kolom nullable dijadikan null supaya penyimpanan
     * konsisten dengan arti nullable pada migration.
     */
    protected function prepareForValidation(): void
    {
        $nullableFields = ['alamat_kantor', 'kode_pos'];
        $values = [];

        foreach (['nama_desa', 'kecamatan', 'kabupaten', 'provinsi', ...$nullableFields] as $field) {
            $value = $this->input($field);

            if (! is_string($value)) {
                continue;
            }

            $value = trim($value);
            $values[$field] = in_array($field, $nullableFields, true) && $value === '' ? null : $value;
        }

        $this->merge($values);
    }

    /**
     * Menentukan label field yang dibaca pengguna.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'nama_desa' => 'Nama Desa',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'alamat_kantor' => 'Alamat Kantor',
            'kode_pos' => 'Kode Pos',
            'logo' => 'Logo',
        ];
    }

    /**
     * Menentukan pesan validasi Bahasa Indonesia untuk popup Data Desa.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute tidak valid.',
            'max' => ':attribute maksimal :max karakter.',
            'regex' => ':attribute hanya boleh berisi angka maksimal 10 digit.',
            'image' => 'Logo harus berupa gambar PNG atau JPG dengan ukuran maksimal 2 MB.',
            'mimes' => 'Logo harus berupa gambar PNG atau JPG dengan ukuran maksimal 2 MB.',
            'uploaded' => 'Logo gagal diunggah. Pilih file PNG atau JPG maksimal 2 MB.',
        ];
    }
}
