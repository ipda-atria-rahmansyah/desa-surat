<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardTest extends TestCase
{
    public function test_dashboard_displays_dummy_summary(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Grafik Surat per Kategori')
            ->assertSee('140/DS/2025');
    }
}
