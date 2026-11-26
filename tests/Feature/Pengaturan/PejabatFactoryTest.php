<?php

namespace Tests\Feature\Pengaturan;

use App\Models\Pejabat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * Memverifikasi data dan state PejabatFactory terhadap migration
 * `tbmaster_pejabat` tanpa memakai seeder.
 */
class PejabatFactoryTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Factory menyimpan satu pejabat pada tabel yang benar dan membiarkan
     * primary key dibuat otomatis oleh database.
     */
    public function test_factory_menyimpan_pejabat_dengan_primary_key_otomatis(): void
    {
        $pejabat = Pejabat::factory()->create();

        $this->assertNotNull($pejabat->id_pejabat);
        $this->assertDatabaseHas('tbmaster_pejabat', ['id_pejabat' => $pejabat->id_pejabat]);
        $this->assertDatabaseCount('tbmaster_pejabat', 1);
        $this->assertDatabaseCount('tbtr_surat', 0);
    }

    /**
     * Default factory mengisi kolom wajib dengan panjang dan tipe yang sesuai.
     */
    public function test_default_factory_mengikuti_constraint_kolom(): void
    {
        $pejabat = Pejabat::factory()->make();

        $this->assertNotEmpty($pejabat->nama_pejabat);
        $this->assertLessThanOrEqual(150, mb_strlen($pejabat->nama_pejabat));
        $this->assertNotEmpty($pejabat->jabatan);
        $this->assertLessThanOrEqual(100, mb_strlen($pejabat->jabatan));
        $this->assertMatchesRegularExpression('/^\d{18}$/', $pejabat->nip);
        $this->assertLessThanOrEqual(30, mb_strlen($pejabat->nip));
        $this->assertLessThanOrEqual(100, mb_strlen($pejabat->pangkat));
        $this->assertTrue($pejabat->status_aktif);
        $this->assertIsBool($pejabat->status_aktif);
    }

    /**
     * Count factory menghasilkan NIP berbeda dalam satu batch agar data test
     * tidak bertabrakan meskipun migration tidak menetapkan unique constraint.
     */
    public function test_count_factory_menghasilkan_nip_berbeda(): void
    {
        $pejabat = Pejabat::factory()->count(5)->make();

        $this->assertCount(5, $pejabat);
        $this->assertCount(5, $pejabat->pluck('nip')->unique());
    }

    /**
     * State status dan jabatan mengubah hanya nilai yang ditargetkan.
     */
    public function test_state_status_dan_jabatan(): void
    {
        $aktif = Pejabat::factory()->aktif()->make();
        $nonaktif = Pejabat::factory()->nonaktif()->make();
        $kepalaDesa = Pejabat::factory()->kepalaDesa()->make();
        $sekretarisDesa = Pejabat::factory()->sekretarisDesa()->make();

        $this->assertTrue($aktif->status_aktif);
        $this->assertFalse($nonaktif->status_aktif);
        $this->assertSame('Kepala Desa', $kepalaDesa->jabatan);
        $this->assertSame('Sekretaris Desa', $sekretarisDesa->jabatan);
    }

    /**
     * State nullable mengosongkan NIP dan pangkat tanpa menyentuh kolom lain.
     */
    public function test_state_nullable_menghasilkan_nilai_null(): void
    {
        $pejabat = Pejabat::factory()->tanpaNip()->tanpaPangkat()->make();

        $this->assertNull($pejabat->nip);
        $this->assertNull($pejabat->pangkat);
        $this->assertNotEmpty($pejabat->nama_pejabat);
        $this->assertNotEmpty($pejabat->jabatan);
    }

    /**
     * State waktu membuat updated_at tepat sesuai permintaan dan created_at
     * tidak berada setelah updated_at.
     */
    public function test_state_diperbarui_pada_menyimpan_waktu_deterministik(): void
    {
        $waktu = Carbon::parse('2026-01-15 10:30:00', 'Asia/Jakarta');
        $pejabat = Pejabat::factory()->diperbaruiPada($waktu)->create();

        $this->assertTrue($pejabat->updated_at->equalTo($waktu));
        $this->assertTrue($pejabat->created_at->lessThanOrEqualTo($pejabat->updated_at));
    }

    /**
     * Factory Pejabat tidak membuat record pada tabel lain ketika memakai
     * default state-nya.
     */
    public function test_factory_tidak_menyentuh_tabel_lain(): void
    {
        Pejabat::factory()->make();

        $this->assertDatabaseCount('tbmaster_pejabat', 0);
        $this->assertDatabaseCount('tbtr_surat', 0);
    }
}
