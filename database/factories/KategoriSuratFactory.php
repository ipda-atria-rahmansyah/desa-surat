<?php

namespace Database\Factories;

use App\Models\KategoriSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Membuat master kategori surat fiktif untuk kebutuhan data arsip.
 *
 * @extends Factory<KategoriSurat>
 */
class KategoriSuratFactory extends Factory
{
    protected $model = KategoriSurat::class;

    /**
     * Menyediakan nilai master kategori yang valid untuk foreign key surat.
     *
     * @return array<string, string>
     */
    public function definition(): array
    {
        return [
            'nama_kategori' => fake()->unique()->words(2, true),
            'kode_nomor' => fake()->unique()->bothify('KAT-##'),
            'deskripsi' => fake()->sentence(),
        ];
    }
}
