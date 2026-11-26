<?php

namespace Database\Factories;

use App\Models\Pejabat;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Membuat data pejabat fiktif untuk feature test dan pengembangan lokal.
 *
 * @extends Factory<Pejabat>
 */
class PejabatFactory extends Factory
{
    protected $model = Pejabat::class;

    /**
     * Menyediakan atribut pejabat tanpa memakai identitas contoh dari gambar.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_pejabat' => fake('id_ID')->name(),
            'jabatan' => fake()->randomElement([
                'Kepala Desa',
                'Sekretaris Desa',
                'Kaur Keuangan',
                'Kasi Pemerintahan',
            ]),
            'nip' => fake()->optional()->numerify('##################'),
            'pangkat' => fake()->optional()->randomElement(['III/a', 'III/b', 'IV/a']),
            'status_aktif' => true,
        ];
    }

    /**
     * Menghasilkan pejabat aktif untuk kebutuhan ringkasan dan filter.
     */
    public function aktif(): static
    {
        return $this->state(fn (array $attributes): array => ['status_aktif' => true]);
    }

    /**
     * Menghasilkan pejabat nonaktif untuk pengujian status.
     */
    public function nonaktif(): static
    {
        return $this->state(fn (array $attributes): array => ['status_aktif' => false]);
    }
}
