<?php

namespace Database\Factories;

use App\Models\Desa;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Warga> */
class WargaFactory extends Factory
{
    protected $model = Warga::class;

    public function definition(): array
    {
        return [
            'nama_lengkap' => fake()->name(),
            'no_kk' => fake()->numerify('################'),
            'nik' => fake()->unique()->numerify('################'),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->date(),
            'jenis_kelamin' => fake()->randomElement(['Laki-laki', 'Perempuan']),
            'alamat' => fake()->address(),
            'desa_id' => Desa::factory(),
            'create_by' => User::factory(),
            'is_active' => true,
        ];
    }

    /**
     * Menyiapkan warga nonaktif untuk menguji bahwa arsip tetap aman namun
     * metrik warga aktif tidak menghitung record tersebut.
     */
    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => ['is_active' => false]);
    }
}
