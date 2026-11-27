<?php

namespace Tests\Feature\Pengaturan;

use App\Models\Desa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DataDesaPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin dapat melihat data desa yang berasal dari database.
     */
    public function test_admin_can_view_data_desa_from_database(): void
    {
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create([
            'nama_desa' => 'Gunajaya',
            'kecamatan' => 'Ciawi',
            'kabupaten' => 'Tasikmalaya',
            'provinsi' => 'Jawa Barat',
            'alamat_kantor' => 'Jl. Raya Gunajaya No. 01',
            'kode_pos' => '46156',
            'logo' => null,
            'updated_at' => Carbon::create(2025, 11, 18, 14, 20),
        ]);

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('Data Desa')
            ->assertSee('Informasi detail desa yang terdaftar dalam sistem.')
            ->assertSee($desa->nama_desa)
            ->assertSee($desa->alamat_kantor)
            ->assertSee($desa->kecamatan)
            ->assertSee($desa->kabupaten)
            ->assertSee($desa->provinsi)
            ->assertSee($desa->kode_pos)
            ->assertSee('18 Nov 2025 14:20')
            ->assertSee('Logo desa belum tersedia')
            ->assertSee('Edit Data Desa');
    }

    /**
     * Jika ada beberapa record, halaman memakai desa dengan ID terkecil.
     */
    public function test_page_uses_the_first_desa_by_id(): void
    {
        $admin = User::factory()->admin()->create();
        Desa::factory()->create(['nama_desa' => 'Desa Pertama']);
        Desa::factory()->create(['nama_desa' => 'Desa Kedua']);

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('Desa Pertama')
            ->assertDontSee('Desa Kedua');
    }

    /**
     * Halaman menampilkan pesan ramah ketika database belum memiliki desa.
     */
    public function test_empty_desa_data_displays_a_friendly_message(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('Data desa belum tersedia. Silakan tambahkan data desa terlebih dahulu.')
            ->assertDontSee('Edit Data Desa');
    }

    /**
     * Operator tidak dapat membuka halaman Data Desa yang khusus Admin.
     */
    public function test_operator_cannot_view_data_desa(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)->get(route('settings.data-desa'))
            ->assertForbidden();
    }

    /**
     * Tamu diarahkan ke login karena seluruh Pengaturan membutuhkan autentikasi.
     */
    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('settings.data-desa'))
            ->assertRedirect(route('login'));
    }

    /**
     * Field nullable tidak menghasilkan error teknis atau nilai kosong yang membingungkan.
     */
    public function test_nullable_desa_fields_use_friendly_fallbacks(): void
    {
        $admin = User::factory()->admin()->create();
        Desa::factory()->create([
            'alamat_kantor' => null,
            'kode_pos' => null,
            'logo' => null,
        ]);

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('Logo desa belum tersedia')
            ->assertSee('Belum tersedia');
    }
}
