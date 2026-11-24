<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Arsip\FilterArsipRequest;
use App\Http\Requests\Warga\FilterWargaRequest;
use App\Models\Surat;
use App\Models\Warga;
use Illuminate\Http\Response;
use Illuminate\View\View;

class ModulePageController extends Controller
{
    public function warga(FilterWargaRequest $request): View|Response
    {
        $query = Warga::query()->orderBy('id_warga');
        $term = $request->validated('q');

        if ($term !== null && $term !== '') {
            $escaped = addcslashes($term, '%_\\');
            $query->where(function ($query) use ($escaped, $term) {
                if (ctype_digit($term)) {
                    $query->where('nik', 'like', "%{$escaped}%");
                } else {
                    $query->where(function ($query) use ($escaped) {
                        $query->where('nama_lengkap', 'like', "%{$escaped}%")
                            ->orWhere('alamat', 'like', "%{$escaped}%");
                    });
                }
            });
        }

        $warga = $query->paginate(10)->withQueryString();
        $data = ['warga' => $warga];

        if ($request->ajax()) {
            return response()->view('pages.warga.partials.results', $data);
        }

        return view('pages.warga.index', $data);
    }

    /**
     * Menampilkan arsip surat terbaru dengan pencarian dan rentang tanggal.
     *
     * Eager loading warga dan jenis surat mencegah query N+1 saat tabel
     * dirender; enam record per halaman mengikuti kebutuhan desain arsip.
     *
     * @param  FilterArsipRequest  $request  Parameter filter yang tervalidasi.
     * @return View Halaman arsip beserta paginator surat.
     */
    public function arsip(FilterArsipRequest $request): View
    {
        $filters = $request->validated();
        $query = Surat::query()
            ->with(['warga', 'jenisSurat'])
            ->latest('tanggal_surat');

        if (($filters['q'] ?? '') !== '') {
            $escaped = addcslashes($filters['q'], '%_');

            $query->where(function ($query) use ($escaped): void {
                $pattern = "%{$escaped}%";
                $query->where('nomor_surat', 'like', $pattern)
                    ->orWhereHas('warga', fn ($warga) => $warga->where('nama_lengkap', 'like', $pattern))
                    ->orWhereHas('jenisSurat', fn ($jenis) => $jenis->where('nama_jenis', 'like', $pattern));
            });
        }

        if (($filters['tanggal_mulai'] ?? '') !== '') {
            $query->whereDate('tanggal_surat', '>=', $filters['tanggal_mulai']);
        }

        if (($filters['tanggal_selesai'] ?? '') !== '') {
            $query->whereDate('tanggal_surat', '<=', $filters['tanggal_selesai']);
        }

        return view('pages.arsip.index', [
            'surat' => $query->paginate(6)->withQueryString(),
        ]);
    }

    public function rekap(): View
    {
        return view('pages.rekap.index');
    }

    public function pengaturan(): View
    {
        return view('pages.pengaturan.index');
    }
}
