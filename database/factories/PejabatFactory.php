<?php

namespace Database\Factories;

use App\Models\Pejabat;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Membuat data pejabat penandatangan fiktif untuk feature test dan pengembangan lokal.
 *
 * Factory ini mengikuti tabel `tbmaster_pejabat`; `id_pejabat` tidak pernah
 * diisi karena dibuat otomatis oleh database. Default berisi nama Indonesia,
 * jabatan perangkat desa, NIP numerik 18 digit, pangkat, dan status aktif.
 * State yang tersedia: aktif, nonaktif, kepalaDesa, sekretarisDesa, tanpaNip,
 * tanpaPangkat, dan diperbaruiPada. Contoh: `Pejabat::factory()->aktif()->create()`,
 * `Pejabat::factory()->count(3)->make()`, dan
 * `Pejabat::factory()->nonaktif()->diperbaruiPada(now()->subDay())->create()`.
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
                'Kaur Umum dan Perencanaan',
                'Kasi Pemerintahan',
                'Kasi Kesejahteraan',
                'Kasi Pelayanan',
            ]),
            'nip' => fake()->unique()->numerify('##########'.'####'.'#'.'###'),
            'pangkat' => fake()->randomElement([
                'Penata Muda (III/a)',
                'Penata Muda Tingkat I (III/b)',
                'Penata (III/c)',
                'Pembina (IV/a)',
            ]),
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

    /**
     * Menghasilkan pejabat dengan jabatan Kepala Desa.
     */
    public function kepalaDesa(): static
    {
        return $this->state(fn (array $attributes): array => ['jabatan' => 'Kepala Desa']);
    }

    /**
     * Menghasilkan pejabat dengan jabatan Sekretaris Desa.
     */
    public function sekretarisDesa(): static
    {
        return $this->state(fn (array $attributes): array => ['jabatan' => 'Sekretaris Desa']);
    }

    /**
     * Mengosongkan NIP untuk menguji kolom nullable sesuai migration.
     */
    public function tanpaNip(): static
    {
        return $this->state(fn (array $attributes): array => ['nip' => null]);
    }

    /**
     * Mengosongkan pangkat untuk menguji kolom nullable sesuai migration.
     */
    public function tanpaPangkat(): static
    {
        return $this->state(fn (array $attributes): array => ['pangkat' => null]);
    }

    /**
     * Mengatur waktu pembaruan dan memastikan waktu pembuatan tidak melampaui.
     *
     * @param  DateTimeInterface  $waktu  Waktu pembaruan yang diinginkan.
     */
    public function diperbaruiPada(DateTimeInterface $waktu): static
    {
        return $this->state(function (array $attributes) use ($waktu): array {
            $createdAt = $attributes['created_at'] ?? null;

            if (! $createdAt instanceof DateTimeInterface || $createdAt > $waktu) {
                $createdAt = $waktu;
            }

            return [
                'created_at' => $createdAt,
                'updated_at' => $waktu,
            ];
        });
    }
}
