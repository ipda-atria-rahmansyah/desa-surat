<?php

namespace Database\Seeders;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Mengisi data awal aplikasi agar halaman yang memakai database dapat
     * langsung diuji setelah `php artisan migrate:fresh --seed`.
     *
     * Urutan dibuat dari data induk ke transaksi untuk menjaga foreign key:
     * warga dan pengguna dibuat lebih dahulu, lalu master surat, kemudian
     * record arsip surat.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
        ]);

        $warga = Warga::factory()->count(10)->create();
        $operator = User::query()->where('role', 'operator')->firstOrFail();
        $kategori = KategoriSurat::factory()->create([
            'nama_kategori' => 'Kependudukan',
            'kode_nomor' => 'KPD',
        ]);
        $jenis = JenisSurat::factory()->create([
            'kategori_surat_id' => $kategori->getKey(),
            'nama_jenis' => 'Surat Keterangan Domisili',
        ]);

        Surat::factory()
            ->count(15)
            ->state([
                'kategori_surat_id' => $kategori->getKey(),
                'jenis_surat_id' => $jenis->getKey(),
                'user_id' => $operator->getKey(),
            ])
            ->sequence(fn ($sequence): array => [
                'warga_id' => $warga[$sequence->index % $warga->count()]->getKey(),
                'nomor_urut' => $sequence->index + 1,
                'nomor_surat' => sprintf('%03d/DS/2026', $sequence->index + 1),
                'tahun_nomor' => 2026,
                'tanggal_surat' => now()->subDays($sequence->index)->toDateString(),
            ])
            ->create();
    }
}
