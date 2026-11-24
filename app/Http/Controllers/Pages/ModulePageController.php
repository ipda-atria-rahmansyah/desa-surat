<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Http\Requests\Arsip\FilterArsipRequest;
use App\Http\Requests\Rekap\FilterRekapRequest;
use App\Http\Requests\Warga\FilterWargaRequest;
use App\Models\Surat;
use App\Models\Warga;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
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

    /**
     * Menampilkan rekap arsip berdasarkan empat kategori resmi dan periode.
     *
     * Tanpa filter tanggal, periode dibatasi 30 hari terakhir agar halaman
     * mengikuti kebutuhan laporan terbaru. Pengelompokan dilakukan setelah
     * query selesai supaya kompatibel dengan MySQL dan SQLite pada test.
     *
     * @param  FilterRekapRequest  $request  Filter rekap yang tervalidasi.
     * @return View Halaman rekap dengan seluruh agregasi server-side.
     */
    public function rekap(FilterRekapRequest $request): View
    {
        $filters = $request->validated();
        $endDate = isset($filters['tanggal_selesai']) && $filters['tanggal_selesai'] !== ''
            ? Carbon::parse($filters['tanggal_selesai'])->endOfDay()
            : Carbon::today()->endOfDay();
        $startDate = isset($filters['tanggal_mulai']) && $filters['tanggal_mulai'] !== ''
            ? Carbon::parse($filters['tanggal_mulai'])->startOfDay()
            : $endDate->copy()->subDays(29)->startOfDay();

        $query = Surat::query()
            ->with(['kategoriSurat', 'warga'])
            ->whereBetween('tanggal_surat', [$startDate, $endDate]);

        if (($filters['kategori'] ?? '') !== '') {
            $query->whereHas('kategoriSurat', function ($kategori) use ($filters): void {
                $kategori->where('nama_kategori', $filters['kategori']);
            });
        }

        $surat = $query->get();
        $kategoriLabels = [
            'kematian' => 'Kematian',
            'kelahiran' => 'Kelahiran',
            'keterangan_umum' => 'Keterangan Umum',
            'pindah' => 'Pindah',
        ];
        $detail = collect($kategoriLabels)->map(function (string $label, string $key) use ($surat): array {
            $jumlah = $surat->filter(fn (Surat $item): bool => strtolower((string) $item->kategoriSurat?->nama_kategori) === $key)->count();

            return ['key' => $key, 'label' => $label, 'jumlah' => $jumlah];
        })->values();
        $total = $surat->count();
        $warga = $surat->whereNotNull('warga_id')->count();
        $previousStart = $startDate->copy()->subDays($startDate->diffInDays($endDate) + 1);
        $previousEnd = $startDate->copy()->subDay()->endOfDay();
        $previousQuery = Surat::query()->whereBetween('tanggal_surat', [$previousStart, $previousEnd]);

        if (($filters['kategori'] ?? '') !== '') {
            $previousQuery->whereHas('kategoriSurat', function ($kategori) use ($filters): void {
                $kategori->where('nama_kategori', $filters['kategori']);
            });
        }

        $previousTotal = $previousQuery->count();
        $change = $previousTotal === 0 ? ($total > 0 ? 100 : 0) : round((($total - $previousTotal) / $previousTotal) * 100);
        $monthly = $surat->groupBy(fn (Surat $item): string => $item->tanggal_surat?->format('Y-m'))->map->count();

        return view('pages.rekap.index', [
            'detail' => $detail,
            'monthly' => $monthly,
            'total' => $total,
            'suratUntukWarga' => $warga,
            'suratLainnya' => $total - $warga,
            'change' => $change,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'selectedCategory' => $filters['kategori'] ?? '',
        ]);
    }

    public function pengaturan(): View
    {
        return view('pages.pengaturan.index');
    }
}
