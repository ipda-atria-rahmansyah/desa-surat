<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Membuat master jenis surat fiktif yang terhubung ke kategori surat.
 *
 * @extends Factory<JenisSurat>
 */
class JenisSuratFactory extends Factory
{
    protected $model = JenisSurat::class;

    /**
     * Menyediakan jenis surat aktif agar dapat dipakai oleh SuratFactory.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kategori_surat_id' => KategoriSurat::factory(),
            'nama_jenis' => fake()->unique()->sentence(3),
            'deskripsi' => fake()->sentence(),
            'is_active' => true,
        ];
    }

    /**
     * Menghubungkan jenis surat dengan kategori master yang sudah ada.
     *
     * @param  KategoriSurat  $kategori  Master kategori tujuan.
     */
    public function dalamKategori(KategoriSurat $kategori): static
    {
        return $this->state(fn (array $attributes): array => [
            'kategori_surat_id' => $kategori->getKey(),
        ]);
    }
}
