<?php

namespace Tests\Feature\Akses;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_operator_can_access_operator_pages_but_not_settings(): void
    {
        $user = User::factory()->operator()->create();
        foreach (['/', '/buat-surat', '/data-warga', '/arsip-surat', '/rekapitulasi-surat'] as $url) {
            $this->actingAs($user)->get($url)->assertOk();
        }
        $this->actingAs($user)->get('/pengaturan')->assertForbidden();
        $this->actingAs($user)->get('/')->assertDontSee('Pengaturan');
    }

    public function test_admin_can_access_settings_and_sees_menu(): void
    {
        $this->actingAs(User::factory()->admin()->create())->get('/pengaturan')
            ->assertRedirect(route('settings.pengguna'));
    }
}
