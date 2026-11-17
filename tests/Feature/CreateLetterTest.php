<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateLetterTest extends TestCase
{
    public function test_create_letter_step_four_is_displayed(): void
    {
        $this->get('/buat-surat')
            ->assertOk()
            ->assertSee('Buat Surat')
            ->assertSee('Data Warga')
            ->assertSee('Data Surat')
            ->assertSee('Simpan &amp; Lanjutkan', false);
    }
}
