<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_displays_dummy_summary(): void
    {
        $this->actingAs(User::factory()->operator()->create())->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Grafik Surat per Kategori')
            ->assertSee('140/DS/2025');
    }
}
