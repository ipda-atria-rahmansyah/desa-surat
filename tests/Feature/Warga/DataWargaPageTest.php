<?php

namespace Tests\Feature\Warga;

use App\Models\User;
use App\Support\Dummy\WargaDummy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DataWargaPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get('/data-warga')->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_see_data_warga_page_and_dummy_rows(): void
    {
        $response = $this->actingAs(User::factory()->operator()->create())->get(route('warga.index'));

        $response->assertOk()
            ->assertSee('Data Warga')
            ->assertSee('Kelola data warga untuk kebutuhan administrasi surat-menyurat.')
            ->assertSee('Tambah Warga');

        foreach (WargaDummy::all() as $item) {
            $response->assertSee($item['nik'])->assertSee($item['nama'])->assertSee($item['alamat']);
        }
    }
}
