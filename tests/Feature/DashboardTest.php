<?php

namespace Tests\Feature;

use App\Models\Desa;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Tamu tidak boleh membaca ringkasan dashboard.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/')->assertRedirect(route('login'));
    }

    /**
     * Dashboard menghitung warga aktif, surat operator, surat desa, dan selesai.
     */
    public function test_operator_sees_scoped_database_summary_without_dummy_data(): void
    {
        $operator = User::factory()->operator()->create(['name' => 'Operator Utama']);
        $operatorLain = User::factory()->operator()->create();
        $desa = Desa::factory()->create();
        $desaLain = Desa::factory()->create();
        $wargaAktif = Warga::factory()->create(['desa_id' => $desa->getKey(), 'is_active' => true]);
        $wargaNonaktif = Warga::factory()->inactive()->create(['desa_id' => $desa->getKey()]);
        $wargaLain = Warga::factory()->create(['desa_id' => $desaLain->getKey()]);
        Surat::factory()->kategoriResmi('Surat Keterangan Umum')->dibuatOleh($operator)->untukWarga($wargaAktif)->create(['status' => 'selesai']);
        Surat::factory()->kategoriResmi('Surat Kematian')->dibuatOleh($operator)->untukWarga($wargaNonaktif)->create(['status' => 'draft']);
        Surat::factory()->kategoriResmi('Surat Pindah')->dibuatOleh($operatorLain)->untukWarga($wargaAktif)->create(['status' => 'selesai']);
        Surat::factory()->kategoriResmi('Surat Kelahiran')->dibuatOleh($operatorLain)->untukWarga($wargaLain)->create(['status' => 'selesai']);
        $this->actingAs($operator)->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Grafik Surat per Kategori')
            ->assertSee('Operator Utama')
            ->assertSee('Total Warga Aktif')
            ->assertSee('Surat Saya')
            ->assertSee('Kematian')
            ->assertSee('Kelahiran')
            ->assertSee('Keterangan Umum')
            ->assertSee('Pindah')
            ->assertDontSee('Kependudukan')
            ->assertDontSee('140/DS/2025');
    }

    /**
     * Dashboard tetap menampilkan angka nol dan empty state tanpa data surat.
     */
    public function test_dashboard_displays_zero_and_empty_state_when_operator_has_no_letters(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get('/')
            ->assertOk()
            ->assertSee('>0</div>', false)
            ->assertSee('Belum ada surat yang dibuat.')
            ->assertSee('Kematian')
            ->assertSee('Kelahiran')
            ->assertSee('Keterangan Umum')
            ->assertSee('Pindah');
    }
}
