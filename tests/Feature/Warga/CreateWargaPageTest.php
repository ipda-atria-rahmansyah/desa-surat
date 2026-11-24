<?php

namespace Tests\Feature\Warga;

use App\Models\Desa;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateWargaPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('warga.create'))->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_create_page(): void
    {
        $user = User::factory()->operator()->create();

        $this->actingAs($user)->get(route('warga.create'))
            ->assertOk()
            ->assertSee('Tambah Data Warga')
            ->assertSee('Informasi Data Warga')
            ->assertSee('Masukkan NIK warga');
    }

    public function test_empty_form_is_rejected(): void
    {
        $user = User::factory()->operator()->create();

        $this->actingAs($user)->post(route('warga.store'), [])
            ->assertSessionHasErrors(['nik', 'nama_lengkap', 'alamat']);
    }

    public function test_invalid_nik_is_rejected(): void
    {
        $user = User::factory()->operator()->create();

        $this->actingAs($user)->post(route('warga.store'), $this->validData(['nik' => '123']))
            ->assertSessionHasErrors('nik');
    }

    public function test_duplicate_nik_is_rejected(): void
    {
        $user = User::factory()->operator()->create();
        Warga::factory()->create(['nik' => '3201010101010001']);

        $this->actingAs($user)->post(route('warga.store'), $this->validData())
            ->assertSessionHasErrors('nik');
    }

    public function test_invalid_name_and_address_are_rejected(): void
    {
        $user = User::factory()->operator()->create();

        $this->actingAs($user)->post(route('warga.store'), $this->validData([
            'nama_lengkap' => 'A',
            'alamat' => str_repeat('a', 256),
        ]))->assertSessionHasErrors(['nama_lengkap', 'alamat']);
    }

    public function test_valid_data_is_stored_and_redirected(): void
    {
        $user = User::factory()->operator()->create();
        $desa = Desa::factory()->create();

        $this->actingAs($user)->post(route('warga.store'), $this->validData())
            ->assertRedirect(route('warga.index'))
            ->assertSessionHas('success', 'Data warga berhasil disimpan.');

        $this->assertDatabaseHas('tbmaster_warga', [
            'nik' => '3201010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Jl. Melati No. 12',
            'desa_id' => $desa->getKey(),
            'create_by' => $user->getKey(),
        ]);
    }

    public function test_add_button_links_to_create_page(): void
    {
        $user = User::factory()->operator()->create();

        $this->actingAs($user)->get(route('warga.index'))
            ->assertOk()
            ->assertSee('href="'.route('warga.create').'"', false);
    }

    private function validData(array $overrides = []): array
    {
        return array_merge([
            'nik' => '3201010101010001',
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Jl. Melati No. 12',
        ], $overrides);
    }
}