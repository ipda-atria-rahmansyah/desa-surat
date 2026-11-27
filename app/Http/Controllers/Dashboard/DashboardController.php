<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Warga;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

/**
 * Menyediakan ringkasan Dashboard Operator dari arsip surat dan data warga.
 *
 * Scope operator memakai user login, sedangkan scope desa diturunkan melalui
 * relasi warga karena tabel pengguna belum menyimpan desa_id.
 */
class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard dengan agregat terpisah untuk operator dan desa.
     *
     * @param  Request  $request  Request web yang sudah melewati middleware auth.
     * @return View Halaman dashboard dengan data agregat dan surat terbaru.
     */
    public function __invoke(Request $request): View
    {
        $userId = $request->user()->getAuthIdentifier();
        $desaIds = $this->desaIdsForOperator($userId);
        $desaSurat = $this->suratDesaQuery($desaIds);
        $suratOperator = (clone $desaSurat)->where('user_id', $userId);

        $categories = $this->categoryStatistics($desaSurat);

        return view('pages.dashboard.dashboard', [
            'operatorName' => $request->user()->name,
            'todayLabel' => now()->translatedFormat('l, d F Y'),
            'statistics' => $this->statistics($userId, $desaIds, $desaSurat),
            'letters' => $this->latestLetters($suratOperator),
            'categories' => $categories,
            'categoryTotal' => collect($categories)->sum('value'),
        ]);
    }

    /**
     * Menentukan desa yang terkait dengan surat operator login.
     *
     * @param  int|string  $userId  ID pengguna yang berasal dari session auth.
     * @return array<int, int> Daftar ID desa yang boleh masuk scope dashboard.
     */
    private function desaIdsForOperator(int|string $userId): array
    {
        return Warga::query()
            ->whereHas('surat', fn (Builder $query) => $query->where('user_id', $userId))
            ->distinct()
            ->pluck('desa_id')
            ->map(static fn (mixed $desaId): int => (int) $desaId)
            ->all();
    }

    /**
     * Membuat builder dasar surat yang dibatasi pada desa terkait operator.
     *
     * @param  array<int, int>  $desaIds  Desa hasil resolusi server-side.
     * @return Builder<Surat> Builder surat dengan filter relasi warga.
     */
    private function suratDesaQuery(array $desaIds): Builder
    {
        return Surat::query()->whereHas(
            'warga',
            fn (Builder $query) => $query->whereIn('desa_id', $desaIds),
        );
    }

    /**
     * Menghitung empat kartu statistik dashboard.
     *
     * @param  int|string  $userId  ID operator login.
     * @param  array<int, int>  $desaIds  Desa yang terkait operator.
     * @param  Builder<Surat>  $desaSurat  Builder dasar surat desa.
     * @return array<int, array<string, int|string>> Data kartu statistik.
     */
    private function statistics(int|string $userId, array $desaIds, Builder $desaSurat): array
    {
        $suratSaya = (clone $desaSurat)->where('user_id', $userId)->count();
        $totalSurat = (clone $desaSurat)->count();
        $suratSelesai = (clone $desaSurat)->where('status', 'selesai')->count();
        $wargaAktif = Warga::query()
            ->whereIn('desa_id', $desaIds)
            ->where('is_active', true)
            ->count();

        return [
            ['label' => 'Total Warga Aktif', 'value' => $wargaAktif, 'note' => 'Warga aktif pada desa terkait', 'icon' => 'bi-people-fill', 'tone' => 'blue'],
            ['label' => 'Surat Saya', 'value' => $suratSaya, 'note' => 'Surat yang dibuat operator', 'icon' => 'bi-file-earmark-text-fill', 'tone' => 'green'],
            ['label' => 'Total Surat', 'value' => $totalSurat, 'note' => 'Seluruh surat desa terkait', 'icon' => 'bi-folder-fill', 'tone' => 'orange'],
            ['label' => 'Surat Selesai', 'value' => $suratSelesai, 'note' => 'Status selesai', 'icon' => 'bi-check-circle-fill', 'tone' => 'purple'],
        ];
    }

    /**
     * Mengambil lima surat terbaru operator beserta relasi yang ditampilkan.
     *
     * @param  Builder<Surat>  $query  Builder surat operator yang sudah terscope.
     * @return array<int, array<string, string>> Baris siap dirender view.
     */
    private function latestLetters(Builder $query): array
    {
        return $query
            ->with(['warga:id_warga,nama_lengkap', 'jenisSurat:id_jenis_surat,nama_jenis'])
            ->latest('tanggal_surat')
            ->latest('id_surat')
            ->limit(5)
            ->get()
            ->map(static fn (Surat $surat): array => [
                'number' => $surat->nomor_surat,
                'type' => $surat->jenisSurat?->nama_jenis ?? 'Jenis surat tidak tersedia',
                'resident' => $surat->warga?->nama_lengkap ?? 'Warga tidak tersedia',
                'date' => $surat->tanggal_surat->translatedFormat('d M Y'),
                'status' => $surat->status,
            ])
            ->all();
    }

    /**
     * Menghitung statistik empat kategori resmi dari seluruh surat desa.
     *
     * @param  Builder<Surat>  $query  Builder surat desa yang sudah terscope.
     * @return array<int, array<string, int|string>> Kategori resmi dan jumlahnya.
     */
    private function categoryStatistics(Builder $query): array
    {
        $officialCategories = [
            'Surat Kematian' => ['label' => 'Kematian', 'tone' => 'people'],
            'Surat Kelahiran' => ['label' => 'Kelahiran', 'tone' => 'business'],
            'Surat Keterangan Umum' => ['label' => 'Keterangan Umum', 'tone' => 'welfare'],
            'Surat Pindah' => ['label' => 'Pindah', 'tone' => 'other'],
        ];
        $counts = (clone $query)
            ->join('tbmaster_kategori_surat', 'tbtr_surat.kategori_surat_id', '=', 'tbmaster_kategori_surat.id_kategori_surat')
            ->selectRaw('tbmaster_kategori_surat.nama_kategori, COUNT(*) AS total')
            ->groupBy('tbmaster_kategori_surat.nama_kategori')
            ->pluck('total', 'nama_kategori')
            ->map(static fn (mixed $total): int => (int) $total);

        return collect($officialCategories)
            ->map(static fn (array $category, string $name): array => [
                'name' => $category['label'],
                'value' => $counts->get($name, 0),
                'tone' => $category['tone'],
            ])
            ->values()
            ->all();
    }
}
