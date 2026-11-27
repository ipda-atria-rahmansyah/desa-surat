<?php

namespace Tests\Feature\Arsip;

use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArsipSuratPageTest extends TestCase
{
    use RefreshDatabase;

    private function buatUser(): User&Authenticatable
    {
        /** @var User&Authenticatable $user */
        $user = User::factory()->create();

        return $user;
    }

    /**
     * Tamu harus diarahkan ke login sebelum dapat membuka arsip.
     */
    public function test_tamu_diarahkan_ke_halaman_login(): void
    {
        $this->get(route('arsip.index'))
            ->assertRedirect(route('login'));
    }

    /**
     * Operator login dapat melihat elemen utama arsip dan pagination enam data.
     */
    public function test_pengguna_login_melihat_tabel_arsip_dan_pagination(): void
    {
        $user = $this->buatUser();
        Surat::factory()->count(25)->create();

        $response = $this->actingAs($user)->get(route('arsip.index'));

        $response->assertOk()
            ->assertSee('Arsip Surat')
            ->assertSee('Lihat dan kelola arsip surat yang telah dibuat.')
            ->assertSee('Cari nomor surat, nama warga, atau jenis surat...')
            ->assertSee('Nomor Surat')
            ->assertSee('Tanggal Surat')
            ->assertSee('Nama Warga')
            ->assertSee('Jenis Surat')
            ->assertSee('Menampilkan 1–6 dari 25 data')
            ->assertSee('page=2', false);
    }

    /**
     * Data factory tampil di halaman pertama dan data lanjutan tampil di halaman kedua.
     */
    public function test_data_factory_tampil_di_halaman_pertama_dan_kedua(): void
    {
        $user = $this->buatUser();
        $first = Surat::factory()->create([
            'nomor_surat' => '001/DS/2026',
            'tanggal_surat' => '2026-01-01',
        ]);
        $secondPage = Surat::factory()->count(6)->create(['tanggal_surat' => '2026-02-01']);

        $this->actingAs($user)
            ->get(route('arsip.index'))
            ->assertSee($secondPage->last()->nomor_surat)
            ->assertDontSee($first->nomor_surat);

        $this->actingAs($user)
            ->get(route('arsip.index', ['page' => 2]))
            ->assertOk()
            ->assertSee($first->nomor_surat);
    }

    /**
     * Pencarian menemukan nomor, nama warga, dan jenis surat yang sesuai.
     */
    public function test_filter_pencarian_menggunakan_nomor_nama_dan_jenis_surat(): void
    {
        $user = $this->buatUser();
        $warga = Warga::factory()->create(['nama_lengkap' => 'Warga Arsip Terpilih']);
        $kategori = KategoriSurat::factory()->create();
        $jenis = JenisSurat::factory()->create([
            'kategori_surat_id' => $kategori->getKey(),
            'nama_jenis' => 'Surat Khusus Arsip',
        ]);
        $surat = Surat::factory()->untukWarga($warga)->create([
            'kategori_surat_id' => $kategori->getKey(),
            'jenis_surat_id' => $jenis->getKey(),
            'nomor_surat' => 'KHUSUS/DS/2026',
        ]);

        $this->actingAs($user)
            ->get(route('arsip.index', ['q' => 'KHUSUS/DS/2026']))
            ->assertSee($surat->nomor_surat);

        $this->actingAs($user)
            ->get(route('arsip.index', ['q' => 'Warga Arsip Terpilih']))
            ->assertSee($surat->nomor_surat);

        $this->actingAs($user)
            ->get(route('arsip.index', ['q' => 'Surat Khusus Arsip']))
            ->assertSee($surat->nomor_surat);
    }

    /**
     * Rentang tanggal hanya menampilkan surat yang berada di dalam periode.
     */
    public function test_filter_rentang_tanggal_membatasi_data(): void
    {
        $user = $this->buatUser();
        $inside = Surat::factory()->create(['tanggal_surat' => '2026-02-10']);
        $outside = Surat::factory()->create(['tanggal_surat' => '2026-03-10']);

        $this->actingAs($user)
            ->get(route('arsip.index', [
                'tanggal_mulai' => '2026-02-01',
                'tanggal_selesai' => '2026-02-28',
            ]))
            ->assertSee($inside->nomor_surat)
            ->assertDontSee($outside->nomor_surat);
    }

    /**
     * Input tanggal terbalik ditolak oleh Form Request dengan pesan Indonesia.
     */
    public function test_filter_menolak_rentang_tanggal_tidak_valid(): void
    {
        $this->actingAs($this->buatUser())
            ->get(route('arsip.index', [
                'tanggal_mulai' => '2026-03-01',
                'tanggal_selesai' => '2026-02-01',
            ]))
            ->assertSessionHasErrors('tanggal_selesai');
    }

    /**
     * Karakter wildcard diperlakukan sebagai wildcard SQL yang valid.
     */
    public function test_filter_menghindari_wildcard_like(): void
    {
        $user = $this->buatUser();
        $literal = Surat::factory()->create(['nomor_surat' => 'A%1/DS/2026']);
        Surat::factory()->create(['nomor_surat' => 'A991/DS/2026']);

        $this->actingAs($user)
            ->get(route('arsip.index', ['q' => 'A%1']))
            ->assertSee('Menampilkan 1–2 dari 2 data');
    }

    /**
     * Pencarian teks biasa tidak menghasilkan sintaks ESCAPE yang ditolak MariaDB.
     */
    public function test_filter_teks_biasa_berhasil_di_mariadb(): void
    {
        $user = $this->buatUser();
        $surat = Surat::factory()->create(['nomor_surat' => 'JANE/DS/2026']);

        $this->actingAs($user)
            ->get(route('arsip.index', ['q' => 'Jane']))
            ->assertOk()
            ->assertSee($surat->nomor_surat)
            ->assertSee('Menampilkan 1–1 dari 1 data');
    }
}
