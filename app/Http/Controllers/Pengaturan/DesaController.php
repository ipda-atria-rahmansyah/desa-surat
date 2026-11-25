<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\View\View;

/**
 * Menangani halaman baca informasi desa pada modul Pengaturan Admin.
 *
 * Controller ini sengaja hanya menyediakan data tampilan. Fitur edit dan
 * penyimpanan data desa belum termasuk scope milestone M16.
 */
class DesaController extends Controller
{
    /**
     * Menampilkan desa pertama secara deterministik untuk halaman informasi.
     *
     * Sistem dirancang memakai satu desa, tetapi schema masih memungkinkan
     * beberapa record. Pengurutan ID menjaga hasil tetap konsisten tanpa
     * mengubah schema atau menghapus data lain.
     *
     * @return View Halaman informasi desa beserta data desa atau null.
     */
    public function show(): View
    {
        $desa = Desa::query()
            ->select([
                'id_desa',
                'nama_desa',
                'kecamatan',
                'kabupaten',
                'provinsi',
                'alamat_kantor',
                'kode_pos',
                'logo',
                'updated_at',
            ])
            ->orderBy('id_desa')
            ->first();

        return view('pages.pengaturan.data-desa', compact('desa'));
    }
}
