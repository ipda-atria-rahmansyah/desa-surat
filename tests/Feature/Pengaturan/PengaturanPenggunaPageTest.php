<?php

namespace Tests\Feature\Pengaturan;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengaturanPenggunaPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin dapat membuka tiga halaman Pengaturan dan route lama mengarah ke Pengguna.
     */
    public function test_admin_can_open_each_settings_page(): void
    {
        $admin = User::factory()->admin()->create([
            'name' => 'Admin Uji',
            'username' => 'adminuji',
        ]);

        $this->actingAs($admin)->get(route('settings.index'))
            ->assertRedirect(route('settings.pengguna'));
        $this->actingAs($admin)->get(route('settings.pengguna'))
            ->assertOk()
            ->assertSee('Pengaturan Pengguna')
            ->assertSee('Daftar Pengguna');
        $this->actingAs($admin)->get(route('settings.pejabat-penandatangan'))
            ->assertOk()
            ->assertSee('Pejabat Penandatangan');
        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('Data Desa');
    }

    /**
     * Tabel menampilkan atribut User yang disetujui tanpa membocorkan password.
     */
    public function test_pengguna_page_displays_users_without_sensitive_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $operator = User::factory()->operator()->create([
            'name' => 'Operator Uji',
            'username' => 'operatoruji',
        ]);

        $this->actingAs($admin)->get(route('settings.pengguna'))
            ->assertOk()
            ->assertSee('Operator Uji')
            ->assertSee('operatoruji')
            ->assertSee('Administrator')
            ->assertSee('Operator')
            ->assertSee('Total Pengguna')
            ->assertSee('2')
            ->assertDontSee('password')
            ->assertDontSee('remember_token');
    }

    /**
     * Operator tidak boleh membuka seluruh route Pengaturan Admin.
     */
    public function test_operator_cannot_open_settings_pages(): void
    {
        $operator = User::factory()->operator()->create();

        foreach (['settings.index', 'settings.pengguna', 'settings.pejabat-penandatangan', 'settings.data-desa'] as $route) {
            $this->actingAs($operator)->get(route($route))->assertForbidden();
        }
    }

    /**
     * Admin dapat mencari pengguna berdasarkan nama/username dan memfilter role.
     */
    public function test_admin_can_filter_users_by_search_and_role(): void
    {
        $admin = User::factory()->admin()->create();
        User::factory()->operator()->create(['name' => 'Operator Desa', 'username' => 'operator.desa']);
        User::factory()->admin()->create(['name' => 'Admin Cadangan', 'username' => 'admin.cadangan']);

        $this->actingAs($admin)->get(route('settings.pengguna', ['q' => 'operator', 'role' => 'operator']))
            ->assertOk()
            ->assertSee('Operator Desa')
            ->assertDontSee('Admin Cadangan')
            ->assertSee('value="operator"', false);
    }

    /**
     * Filter role di luar daftar resmi ditolak oleh backend dalam bahasa Indonesia.
     */
    public function test_invalid_user_role_filter_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('settings.pengguna', ['role' => 'superadmin']))
            ->assertInvalid(['role']);
    }
}
