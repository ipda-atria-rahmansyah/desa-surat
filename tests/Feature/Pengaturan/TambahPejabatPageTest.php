<?php

namespace Tests\Feature\Pengaturan;

use App\Models\Pejabat;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Menguji form dan penyimpanan pejabat penandatangan sesuai scope M18.
 */
class TambahPejabatPageTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin dapat membuka form, sedangkan tamu dan Operator ditolak sesuai role.
     */
    public function test_only_admin_can_open_create_form(): void
    {
        $admin = User::factory()->admin()->create();
        $operator = User::factory()->operator()->create();

        $this->get(route('settings.pejabat-penandatangan.create'))
            ->assertRedirect(route('login'));
        $this->actingAs($operator)
            ->get(route('settings.pejabat-penandatangan.create'))
            ->assertForbidden();
        $this->actingAs($admin)
            ->get(route('settings.pejabat-penandatangan.create'))
            ->assertOk()
            ->assertSee('Tambah Penjabat Penandatangan');
    }

    /**
     * Admin dapat menyimpan field lengkap dan nilai asing tidak masuk ke model.
     */
    public function test_admin_can_store_complete_pejabat_data(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('settings.pejabat-penandatangan.store'), [
                'nama_pejabat' => '  Pejabat Uji  ',
                'jabatan' => 'Kepala Desa',
                'nip' => '19800101-200001-1-001',
                'pangkat' => 'Pembina (IV/a)',
                'status_aktif' => '1',
                'id_pejabat' => 999999,
                'kolom_tidak_diizinkan' => 'diabaikan',
            ])
            ->assertRedirect(route('settings.pejabat-penandatangan'))
            ->assertSessionHas('success', 'Data pejabat berhasil disimpan.');

        $this->assertDatabaseHas('tbmaster_pejabat', [
            'nama_pejabat' => 'Pejabat Uji',
            'jabatan' => 'Kepala Desa',
            'nip' => '198001012000011001',
            'pangkat' => 'Pembina (IV/a)',
            'status_aktif' => true,
        ]);
        $this->assertDatabaseMissing('tbmaster_pejabat', ['id_pejabat' => 999999]);
    }

    /**
     * Field nullable dapat dikosongkan dan checkbox yang tidak dicentang menjadi false.
     */
    public function test_nullable_fields_and_inactive_status_are_stored_correctly(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->post(route('settings.pejabat-penandatangan.store'), [
                'nama_pejabat' => 'Pejabat Tanpa Data Opsional',
                'jabatan' => 'Sekretaris Desa',
                'nip' => ' ',
                'pangkat' => '',
                'status_aktif' => '0',
            ])
            ->assertRedirect(route('settings.pejabat-penandatangan'));

        $this->assertDatabaseHas('tbmaster_pejabat', [
            'nama_pejabat' => 'Pejabat Tanpa Data Opsional',
            'nip' => null,
            'pangkat' => null,
            'status_aktif' => false,
        ]);
    }

    /**
     * Field wajib, panjang migration, dan format NIP ditolak tanpa menyimpan data.
     */
    public function test_invalid_pejabat_data_is_rejected(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)
            ->from(route('settings.pejabat-penandatangan.create'))
            ->post(route('settings.pejabat-penandatangan.store'), [
                'nama_pejabat' => str_repeat('x', 151),
                'jabatan' => '',
                'nip' => '12345ABC',
                'pangkat' => str_repeat('x', 101),
            ])
            ->assertRedirect(route('settings.pejabat-penandatangan.create'))
            ->assertInvalid(['nama_pejabat', 'jabatan', 'nip', 'pangkat']);

        $this->assertSame(0, Pejabat::query()->count());
    }

    /**
     * Operator tidak dapat membaca atau mengirim endpoint store Admin.
     */
    public function test_operator_cannot_store_pejabat_data(): void
    {
        $operator = User::factory()->operator()->create();

        $this->actingAs($operator)
            ->post(route('settings.pejabat-penandatangan.store'), [
                'nama_pejabat' => 'Tidak Boleh',
                'jabatan' => 'Kepala Desa',
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('tbmaster_pejabat', ['nama_pejabat' => 'Tidak Boleh']);
    }
}
