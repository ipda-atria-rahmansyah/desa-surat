<?php

namespace Tests\Feature;

use Tests\TestCase;

class CreateLetterTest extends TestCase
{
    public function test_create_letter_initial_steps_are_displayed(): void
    {
        $this->get('/buat-surat')
            ->assertOk()
            ->assertSee('Buat Surat')
            ->assertSee('1. Pilih Kategori Surat')
            ->assertSee('2. Pilih Jenis Surat')
            ->assertSee('3. Cari Warga dengan NIK')
            ->assertSee('Masukkan 16 digit NIK')
            ->assertSee('Cari Warga');
    }

    public function test_create_letter_step_four_is_displayed(): void
    {
        $this->get('/buat-surat/langkah-4')
            ->assertOk()
            ->assertSee('Buat Surat')
            ->assertSee('Data Warga')
            ->assertSee('Data Surat')
            ->assertSee('Ubah Data Warga')
            ->assertSee('Simpan &amp; Lanjutkan', false)
            ->assertSee('Data warga sudah terisi otomatis. Silakan lengkapi keperluan surat dan pastikan semua informasi sudah benar.');
    }
}
