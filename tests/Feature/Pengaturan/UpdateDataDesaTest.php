<?php

namespace Tests\Feature\Pengaturan;

use App\Models\Desa;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UpdateDataDesaTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Admin dapat memperbarui field teks dan menerima redirect sukses.
     */
    public function test_admin_can_update_desa_text_fields(): void
    {
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create();

        $response = $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => 'Desa Sukamaju Baru',
            'kecamatan' => 'Kecamatan Baru',
            'kabupaten' => 'Kabupaten Baru',
            'provinsi' => 'Provinsi Baru',
            'alamat_kantor' => 'Jalan Kantor Desa Nomor 10',
            'kode_pos' => '1234567890',
        ]);

        $response->assertRedirect(route('settings.data-desa'))
            ->assertSessionHas('success', 'Data desa berhasil diperbarui.');

        $this->assertDatabaseHas('tbmaster_desa', [
            'id_desa' => $desa->id_desa,
            'nama_desa' => 'Desa Sukamaju Baru',
            'kode_pos' => '1234567890',
        ]);
    }

    /**
     * Validasi kosong menolak perubahan, memakai error bag khusus, dan
     * mempertahankan input lama untuk modal.
     */
    public function test_required_fields_are_validated_in_update_desa_error_bag(): void
    {
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create(['nama_desa' => 'Nama Lama']);

        $response = $this->from(route('settings.data-desa'))
            ->actingAs($admin)
            ->patch(route('settings.data-desa.update'), [
                'nama_desa' => '',
                'kecamatan' => '',
                'kabupaten' => '',
                'provinsi' => '',
            ]);

        $response->assertRedirect(route('settings.data-desa'))
            ->assertSessionHasErrors(['nama_desa', 'kecamatan', 'kabupaten', 'provinsi'], null, 'updateDesa');
        $this->assertSame('Nama Lama', $desa->fresh()->nama_desa);
        $this->assertStringContainsString('Nama Desa wajib diisi.', session('errors')->getBag('updateDesa')->first('nama_desa'));
    }

    /**
     * Panjang field mengikuti batas migration dan kode pos harus numerik maksimal 10 digit.
     */
    public function test_length_and_kode_pos_rules_are_enforced(): void
    {
        $admin = User::factory()->admin()->create();
        Desa::factory()->create();

        $response = $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => str_repeat('a', 151),
            'kecamatan' => str_repeat('a', 101),
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
            'kode_pos' => '12345678901',
        ]);

        $response->assertSessionHasErrors(['nama_desa', 'kecamatan', 'kode_pos'], null, 'updateDesa');
    }

    /**
     * Field nullable yang dikirim kosong disimpan sebagai null.
     */
    public function test_nullable_fields_are_saved_as_null(): void
    {
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create(['alamat_kantor' => 'Alamat Lama', 'kode_pos' => '12345']);

        $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => $desa->nama_desa,
            'kecamatan' => $desa->kecamatan,
            'kabupaten' => $desa->kabupaten,
            'provinsi' => $desa->provinsi,
            'alamat_kantor' => '   ',
            'kode_pos' => '   ',
        ])->assertRedirect(route('settings.data-desa'));

        $this->assertDatabaseHas('tbmaster_desa', [
            'id_desa' => $desa->id_desa,
            'alamat_kantor' => null,
            'kode_pos' => null,
        ]);
    }

    /**
     * Logo valid disimpan dengan nama acak, logo lama dihapus setelah update,
     * sedangkan logo lama dipertahankan jika tidak ada upload baru.
     */
    public function test_valid_logo_replaces_managed_old_logo_and_no_upload_preserves_logo(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $oldLogo = 'desa/logo/logo-lama.png';
        Storage::disk('public')->put($oldLogo, 'old');
        $desa = Desa::factory()->create(['logo' => $oldLogo]);

        $response = $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => $desa->nama_desa,
            'kecamatan' => $desa->kecamatan,
            'kabupaten' => $desa->kabupaten,
            'provinsi' => $desa->provinsi,
            'logo' => UploadedFile::fake()->image('logo-asli.png'),
        ]);

        $response->assertRedirect(route('settings.data-desa'));
        $newLogo = $desa->fresh()->logo;
        $this->assertNotSame($oldLogo, $newLogo);
        $this->assertStringStartsWith('desa/logo/', $newLogo);
        $this->assertStringNotContainsString('logo-asli.png', $newLogo);
        Storage::disk('public')->assertMissing($oldLogo);
        Storage::disk('public')->assertExists($newLogo);

        $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => $desa->fresh()->nama_desa,
            'kecamatan' => $desa->fresh()->kecamatan,
            'kabupaten' => $desa->fresh()->kabupaten,
            'provinsi' => $desa->fresh()->provinsi,
        ])->assertRedirect(route('settings.data-desa'));

        $this->assertSame($newLogo, $desa->fresh()->logo);
        Storage::disk('public')->assertExists($newLogo);
    }

    /**
     * Format logo yang tidak diizinkan dan ukuran di atas 2 MB ditolak.
     */
    public function test_invalid_logo_files_are_rejected(): void
    {
        Storage::fake('public');
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create();

        foreach ([
            UploadedFile::fake()->create('logo.gif', 100, 'image/gif'),
            UploadedFile::fake()->create('logo.svg', 100, 'image/svg+xml'),
            UploadedFile::fake()->create('logo.pdf', 100, 'application/pdf'),
            UploadedFile::fake()->image('logo-besar.jpg')->size(2049),
        ] as $logo) {
            $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
                'nama_desa' => $desa->nama_desa,
                'kecamatan' => $desa->kecamatan,
                'kabupaten' => $desa->kabupaten,
                'provinsi' => $desa->provinsi,
                'logo' => $logo,
            ])->assertSessionHasErrors('logo', null, 'updateDesa');
        }
    }

    /**
     * Operator ditolak dan method PATCH tidak mengubah data ketika role tidak sesuai.
     */
    public function test_update_route_requires_admin_and_patch_method(): void
    {
        $desa = Desa::factory()->create(['nama_desa' => 'Nama Lama']);

        $this->actingAs(User::factory()->operator()->create())
            ->patch(route('settings.data-desa.update'), ['nama_desa' => 'Tidak Boleh'])
            ->assertForbidden();
        $this->assertSame('Nama Lama', $desa->fresh()->nama_desa);
    }

    /**
     * Payload tidak tepercaya diabaikan dan update tetap memakai record ID terkecil.
     */
    public function test_untrusted_identity_and_timestamp_fields_are_ignored(): void
    {
        $admin = User::factory()->admin()->create();
        $first = Desa::factory()->create(['nama_desa' => 'Desa Pertama']);
        $second = Desa::factory()->create(['nama_desa' => 'Desa Kedua']);
        $createdAt = $first->created_at;

        $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'id_desa' => $second->id_desa,
            'created_at' => now()->addYear()->toDateTimeString(),
            'updated_at' => now()->addYear()->toDateTimeString(),
            'nama_desa' => 'Desa Pertama Diubah',
            'kecamatan' => $first->kecamatan,
            'kabupaten' => $first->kabupaten,
            'provinsi' => $first->provinsi,
            'field_tak_dikenal' => 'diabaikan',
        ])->assertRedirect(route('settings.data-desa'));

        $this->assertSame('Desa Pertama Diubah', $first->fresh()->nama_desa);
        $this->assertSame('Desa Kedua', $second->fresh()->nama_desa);
        $this->assertEquals($createdAt, $first->fresh()->created_at);
    }

    /**
     * Tanpa record desa, route memberi alert ramah dan tidak membuat record baru.
     */
    public function test_update_without_desa_returns_friendly_error(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->patch(route('settings.data-desa.update'), [
            'nama_desa' => 'Desa Baru',
            'kecamatan' => 'Kecamatan',
            'kabupaten' => 'Kabupaten',
            'provinsi' => 'Provinsi',
        ])->assertRedirect(route('settings.data-desa'))
            ->assertSessionHas('error', 'Data desa belum tersedia sehingga tidak dapat diperbarui.');

        $this->assertDatabaseCount('tbmaster_desa', 0);
    }

    /**
     * Halaman Admin memuat pemicu modal, field database, CSRF, dan method PATCH.
     */
    public function test_admin_page_contains_edit_modal_and_prefilled_values(): void
    {
        $admin = User::factory()->admin()->create();
        $desa = Desa::factory()->create(['nama_desa' => 'Desa Terisi']);

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertSee('data-bs-target="#editDataDesaModal"', false)
            ->assertSee('id="editDataDesaModal"', false)
            ->assertSee('name="_token"', false)
            ->assertSee('name="_method"', false)
            ->assertSee($desa->nama_desa)
            ->assertSee('name="provinsi"', false);
    }

    /**
     * Halaman tanpa data desa tidak merender tombol maupun modal edit.
     */
    public function test_empty_desa_page_has_no_edit_button_or_modal(): void
    {
        $admin = User::factory()->admin()->create();

        $this->actingAs($admin)->get(route('settings.data-desa'))
            ->assertOk()
            ->assertDontSee('Edit Data Desa')
            ->assertDontSee('id="editDataDesaModal"', false);
    }
}
