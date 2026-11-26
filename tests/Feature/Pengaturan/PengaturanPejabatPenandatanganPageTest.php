<?php

namespace Tests\Feature\Pengaturan;

use App\Models\Pejabat;
use App\Models\Surat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji akses, filter read-only, model, factory, relasi, dan tampilan
 * halaman Pejabat Penandatangan sesuai scope M17.
 */
class PengaturanPejabatPenandatanganPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin dapat membuka halaman, operator ditolak, dan tamu diarahkan login.
     */
    public function test_access_is_limited_to_admin(): void
    {
        $admin = User::factory()->admin()->create();
        $operator = User::factory()->operator()->create();

        $this->get(route('settings.pejabat-penandatangan'))
            ->assertRedirect(route('login'));
        $this->actingAs($operator)->get(route('settings.pejabat-penandatangan'))->assertForbidden();
        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan'))
            ->assertOk()
            ->assertSee('Penjabat Penandatangan');
    }

    /**
     * Model memakai mapping tabel lama, cast boolean, relasi, dan factory valid.
     */
    public function test_model_factory_and_relations_follow_schema(): void
    {
        $pejabat = Pejabat::factory()->aktif()->create();

        $this->assertSame('tbmaster_pejabat', $pejabat->getTable());
        $this->assertSame('id_pejabat', $pejabat->getKeyName());
        $this->assertIsBool($pejabat->status_aktif);
        $this->assertInstanceOf(Pejabat::class, Pejabat::factory()->nonaktif()->create());
        $this->assertSame('pejabat_id', $pejabat->surat()->getForeignKeyName());
        $this->assertSame('pejabat_id', (new Surat)->pejabat()->getForeignKeyName());
    }

    /**
     * Tabel menampilkan data factory, status, tanggal, dan jumlah aktif global.
     */
    public function test_page_displays_factory_data_and_global_active_count(): void
    {
        $admin = User::factory()->admin()->create();
        $aktif = Pejabat::factory()->aktif()->create(['nama_pejabat' => 'Zeta Pejabat']);
        Pejabat::factory()->nonaktif()->create(['nama_pejabat' => 'Alpha Pejabat']);

        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan', ['status' => 'nonaktif']))
            ->assertOk()
            ->assertSee('Alpha Pejabat')
            ->assertDontSee('Zeta Pejabat')
            ->assertSee('Pejabat Aktif')
            ->assertSee('1')
            ->assertSee($aktif->updated_at->locale('id')->translatedFormat('d M Y H:i'));
    }

    /**
     * Pencarian, status, kombinasi, wildcard, dan validasi parameter bekerja.
     */
    public function test_search_status_and_invalid_filters_are_handled(): void
    {
        $admin = User::factory()->admin()->create();
        Pejabat::factory()->aktif()->create(['nama_pejabat' => 'Nama Persen 100%']);
        Pejabat::factory()->nonaktif()->create(['nama_pejabat' => 'Nama Garis Bawah']);

        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan', ['q' => 'Persen']))
            ->assertOk()
            ->assertSee('Nama Persen 100%');
        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan', ['q' => '%']))
            ->assertOk()
            ->assertSee('Data pejabat tidak ditemukan.');
        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan', ['status' => 'lain']))
            ->assertInvalid(['status']);
        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan', ['q' => str_repeat('x', 101)]))
            ->assertInvalid(['q']);
    }

    /**
     * Kondisi kosong tetap memakai pesan yang disepakati dan kontrol visual
     * tidak memiliki form/tautan aksi aktif.
     */
    public function test_empty_state_and_read_only_controls_are_rendered(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan'))
            ->assertOk()
            ->assertSee('Data pejabat tidak ditemukan.')
            ->assertSee('Tambah Penjabat')
            ->assertSee('disabled', false)
            ->assertDontSee('settings.pejabat.create');
    }
}
