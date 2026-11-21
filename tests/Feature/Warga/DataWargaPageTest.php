<?php

namespace Tests\Feature\Warga;

use App\Models\Desa;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataWargaPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/data-warga')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_data_warga_page_and_database_rows(): void
    {
        $user = User::factory()->operator()->create();
        Warga::factory()->create(['nama_lengkap' => 'Ahmad Fauzi', 'nik' => '3201010101010001', 'desa_id' => Desa::factory(), 'create_by' => $user]);
        $response = $this->actingAs($user)->get(route('warga.index'));

        $response->assertOk()
            ->assertSee('Data Warga')
            ->assertSee('Kelola data warga untuk kebutuhan administrasi surat-menyurat.')
            ->assertSee('Tambah Warga');

        $response->assertSee('3201010101010001')->assertSee('Ahmad Fauzi');
    }

    public function test_authenticated_user_can_search_warga_by_address(): void
    {
        $user = User::factory()->operator()->create();
        Warga::factory()->create([
            'nama_lengkap' => 'Budi Santoso',
            'alamat' => 'Jl. Melati No. 12',
            'desa_id' => Desa::factory(),
            'create_by' => $user,
        ]);
        Warga::factory()->create([
            'nama_lengkap' => 'Siti Aminah',
            'alamat' => 'Jl. Mawar No. 8',
            'desa_id' => Desa::factory(),
            'create_by' => $user,
        ]);

        $response = $this->actingAs($user)->get(route('warga.index', ['q' => 'Melati']));

        $response->assertOk()
            ->assertSee('Budi Santoso')
            ->assertSee('Jl. Melati No. 12')
            ->assertDontSee('Siti Aminah');
    }
}
