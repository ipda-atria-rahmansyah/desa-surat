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

    /**
     * Admin dapat membuka form dan menyimpan pengguna baru dengan password hashed.
     */
    public function test_admin_can_create_user_from_settings(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('settings.pengguna.create'))
            ->assertOk()
            ->assertSee('Tambah Pengguna');

        $this->actingAs($admin)->post(route('settings.pengguna.store'), [
            'name' => 'Operator Baru',
            'username' => 'operatorbaru',
            'role' => 'operator',
            'password' => 'passwordbaru',
            'password_confirmation' => 'passwordbaru',
        ])->assertRedirect(route('settings.pengguna'));

        $this->assertDatabaseHas('tbmaster_users', [
            'username' => 'operatorbaru',
            'role' => 'operator',
            'is_active' => true,
        ]);
    }

    /**
     * Operator tidak dapat membuka atau mengirim form tambah pengguna.
     */
    public function test_operator_cannot_create_user(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get(route('settings.pengguna.create'))->assertForbidden();
        $this->actingAs($operator)->post(route('settings.pengguna.store'))->assertForbidden();
    }

    /**
     * Admin dapat membalik status pengguna, tetapi tidak dapat menonaktifkan akun sendiri.
     */
    public function test_admin_can_toggle_other_user_status_but_not_own_status(): void
    {
        $admin = User::factory()->admin()->create(['is_active' => true]);
        $operator = User::factory()->operator()->create(['is_active' => true]);

        $this->actingAs($admin)->post(route('settings.pengguna.toggle-status', $operator))
            ->assertRedirect();
        $this->assertDatabaseHas('tbmaster_users', ['id_user' => $operator->id_user, 'is_active' => false]);

        $this->actingAs($admin)->post(route('settings.pengguna.toggle-status', $admin))
            ->assertRedirect()
            ->assertSessionHas('error');
        $this->assertDatabaseHas('tbmaster_users', ['id_user' => $admin->id_user, 'is_active' => true]);
    }
}
