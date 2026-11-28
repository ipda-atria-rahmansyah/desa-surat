<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengaturan\StorePejabatRequest;
use App\Models\Pejabat;
use App\Support\Activity\ActivityRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

/**
 * Menangani form tambah pejabat penandatangan pada area Pengaturan Admin.
 *
 * Controller ini sengaja hanya memproses tambah sesuai scope M18; edit, hapus,
 * dan perubahan status tetap menjadi pekerjaan terpisah.
 */
class PejabatController extends Controller
{
    /**
     * Menampilkan form pejabat baru dengan status aktif sebagai nilai awal.
     *
     * @return View Halaman form tambah pejabat.
     */
    public function create(): View
    {
        return view('pages.pengaturan.pejabat-penandatangan.create', [
            'statusAktifDefault' => true,
        ]);
    }

    /**
     * Menyimpan pejabat baru memakai hanya atribut yang lolos validasi.
     *
     * @param  StorePejabatRequest  $request  Request Admin yang tervalidasi.
     * @return RedirectResponse Kembali ke daftar dengan pesan sukses.
     */
    public function store(StorePejabatRequest $request, ActivityRecorder $activityRecorder): RedirectResponse
    {
        $pejabat = Pejabat::create($request->validated());
        $activityRecorder->record('CREATE', 'Pejabat', 'Data pejabat berhasil disimpan.', $pejabat);

        return to_route('settings.pejabat-penandatangan')
            ->with('success', 'Data pejabat berhasil disimpan.');
    }
}
