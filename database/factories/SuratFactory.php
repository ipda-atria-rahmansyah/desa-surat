<?php

namespace Database\Factories;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Membuat record surat arsip fiktif sesuai kolom transaksi yang tersedia.
 *
 * Relasi master dibuat melalui factory agar feature test tidak bergantung
 * pada seeder atau array data statis.
 *
 * @extends Factory<Surat>
 */
class SuratFactory extends Factory
{
    protected $model = Surat::class;

    /**
     * Menyediakan surat selesai dengan nomor, tanggal, warga, dan jenis valid.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $year = fake()->numberBetween(2024, 2026);
        $category = KategoriSurat::factory()->create();
        $jenis = JenisSurat::factory()->create(['kategori_surat_id' => $category->getKey()]);

        return [
            'nomor_urut' => fake()->unique()->numberBetween(1, 999),
            'nomor_surat' => fake()->unique()->numberBetween(1, 999).'/DS/'.$year,
            'kategori_surat_id' => $category->getKey(),
            'tahun_nomor' => $year,
            'jenis_surat_id' => $jenis->getKey(),
            'warga_id' => Warga::factory(),
            'user_id' => User::factory(),
            'tanggal_surat' => fake()->dateTimeBetween('-18 months', 'now')->format('Y-m-d'),
            'keperluan' => fake()->sentence(),
            'status' => 'selesai',
        ];
    }

    /**
     * Mengatur surat agar memakai operator yang sudah disediakan test.
     *
     * @param  User  $user  Pengguna pembuat surat.
     */
    public function dibuatOleh(User $user): static
    {
        return $this->state(fn (array $attributes): array => ['user_id' => $user->getKey()]);
    }

    /**
     * Mengatur surat agar memakai warga yang sudah disediakan test.
     *
     * @param  Warga  $warga  Warga pemilik surat.
     */
    public function untukWarga(Warga $warga): static
    {
        return $this->state(fn (array $attributes): array => ['warga_id' => $warga->getKey()]);
    }
}
