<?php

namespace Tests\Feature\Dashboard;

use App\Models\ActivityLog;
use App\Models\Desa;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Memastikan Dashboard Admin hanya menampilkan agregat lintas operator yang aman.
 */
class AdminDashboardTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Operator menerima 403 pada endpoint admin, sementara admin aktif dapat masuk.
     */
    public function test_admin_dashboard_is_authorized_by_role(): void
    {
        $operator = User::factory()->operator()->create();
        $admin = User::factory()->admin()->create();

        $this->actingAs($operator)->get(route('dashboard.admin'))->assertForbidden();
        $this->actingAs($admin)->get(route('dashboard'))->assertOk()->assertSee('Total Pengguna Aktif');
    }

    /**
     * Metrik admin menghitung semua desa, status surat, whitelist kategori, dan jenis aktif.
     */
    public function test_admin_dashboard_shows_cross_operator_metrics_and_four_categories(): void
    {
        $admin = User::factory()->admin()->create();
        $operator = User::factory()->operator()->create();
        $desa = Desa::factory()->create();
        $desaLain = Desa::factory()->create();
        $warga = Warga::factory()->create(['desa_id' => $desa->getKey()]);
        $wargaLain = Warga::factory()->create(['desa_id' => $desaLain->getKey(), 'is_active' => false]);
        $kategori = KategoriSurat::factory()->create(['nama_kategori' => 'Surat Kematian']);
        $jenis = JenisSurat::factory()->create(['kategori_surat_id' => $kategori->getKey(), 'is_active' => true]);

        Surat::factory()->count(2)->sequence(
            ['warga_id' => $warga->getKey(), 'user_id' => $operator->getKey(), 'status' => 'selesai', 'kategori_surat_id' => $kategori->getKey(), 'jenis_surat_id' => $jenis->getKey()],
            ['warga_id' => $wargaLain->getKey(), 'user_id' => $operator->getKey(), 'status' => 'dibatalkan', 'kategori_surat_id' => $kategori->getKey(), 'jenis_surat_id' => $jenis->getKey()],
        )->create();

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Total Warga Aktif')
            ->assertSee('Total Kategori Surat')
            ->assertSee('Total Jenis Surat')
            ->assertSee('Total Pengguna Aktif')
            ->assertSee('Aktivitas Pengguna Terbaru')
            ->assertSee('Kematian')
            ->assertSee('Kelahiran')
            ->assertSee('Keterangan Umum')
            ->assertSee('Pindah');
    }

    /**
     * Dashboard kosong menampilkan nol, donut netral, dan empty state aktivitas.
     */
    public function test_empty_admin_dashboard_has_neutral_states(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('app-donut--empty', false)
            ->assertSee('Belum ada surat terbaru.')
            ->assertSee('Belum ada aktivitas pengguna.');
    }

    /**
     * Aktivitas terbaru menampilkan label Sistem dan tidak mengekspos payload JSON.
     */
    public function test_admin_dashboard_lists_safe_recent_activity(): void
    {
        $admin = User::factory()->admin()->create();

        ActivityLog::create([
            'activity' => 'LOGIN',
            'module' => 'Auth',
            'description' => 'Pengguna berhasil masuk ke aplikasi.',
            'old_values' => ['password' => 'rahasia'],
        ]);

        $this->actingAs($admin)->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Sistem')
            ->assertSee('Pengguna berhasil masuk ke aplikasi.')
            ->assertDontSee('rahasia');
    }
}
