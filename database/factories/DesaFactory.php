<?php

namespace Database\Factories;

use App\Models\Desa;
use Illuminate\Database\Eloquent\Factories\Factory;

/** @extends Factory<Desa> */
class DesaFactory extends Factory
{
    protected $model = Desa::class;

    public function definition(): array
    {
        return ['nama_desa' => fake()->city(), 'kecamatan' => fake()->city(), 'kabupaten' => fake()->city(), 'provinsi' => 'Jawa Barat'];
    }
}
