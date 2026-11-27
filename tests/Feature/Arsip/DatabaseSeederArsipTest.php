<?php

namespace Tests\Feature\Arsip;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DatabaseSeederArsipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Seeder utama harus menyiapkan master dan enam arsip yang dapat dibaca.
     */
    public function test_database_seeder_menghasilkan_data_arsip(): void
    {
        $this->seed();

        $this->assertSame(6, Surat::count());
        $this->assertGreaterThanOrEqual(1, KategoriSurat::count());
        $this->assertGreaterThanOrEqual(1, JenisSurat::count());
        $this->assertNotNull(Surat::with(['warga', 'jenisSurat', 'pembuat'])->first());
    }
}
