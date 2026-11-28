<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Surat;
use App\Models\Warga;
use App\Support\Dashboard\AdminDashboardData;
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
    public function __invoke(Request $request, AdminDashboardData $adminDashboardData): View
    {
        $data = $request->user()->role === 'admin'
            ? $adminDashboardData->all()
            : $this->operatorData($request);

        return view('pages.dashboard.dashboard', [
            'operatorName' => $request->user()->name,
            'isAdmin' => $request->user()->role === 'admin',
            'todayLabel' => now()->translatedFormat('l, d F Y'),
            ...$data,
        ]);
    }

    /**
     * Menyediakan data dashboard operator dari scope user dan desa terkait.
     *
     * @param  Request  $request  Request auth dengan user operator.
     * @return array<string, mixed> Data operator yang kompatibel dengan view.
     */
    private function operatorData(Request $request): array
    {
        $userId = $request->user()->getAuthIdentifier();
        $desaIds = Warga::query()
            ->whereHas('surat', fn (Builder $query) => $query->where('user_id', $userId))
            ->distinct()
            ->pluck('desa_id')
            ->map(static fn (mixed $desaId): int => (int) $desaId)
            ->all();
        $suratDesa = Surat::query()->whereHas('warga', fn (Builder $query) => $query->whereIn('desa_id', $desaIds));
        $suratOperator = (clone $suratDesa)->where('user_id', $userId);
        $categories = $this->operatorCategories($suratDesa);

        return [
            'statistics' => [
                ['label' => 'Total Warga Aktif', 'value' => Warga::query()->whereIn('desa_id', $desaIds)->where('is_active', true)->count(), 'note' => 'Warga aktif pada desa terkait', 'icon' => 'bi-people-fill', 'tone' => 'blue'],
                ['label' => 'Surat Saya', 'value' => (clone $suratOperator)->count(), 'note' => 'Surat yang dibuat operator', 'icon' => 'bi-file-earmark-text-fill', 'tone' => 'green'],
                ['label' => 'Total Surat', 'value' => (clone $suratDesa)->count(), 'note' => 'Seluruh surat desa terkait', 'icon' => 'bi-folder-fill', 'tone' => 'orange'],
                ['label' => 'Surat Selesai', 'value' => (clone $suratDesa)->where('status', 'selesai')->count(), 'note' => 'Status selesai', 'icon' => 'bi-check-circle-fill', 'tone' => 'purple'],
            ],
            'letters' => (clone $suratOperator)->with(['warga:id_warga,nama_lengkap', 'jenisSurat:id_jenis_surat,nama_jenis'])->latest('tanggal_surat')->latest('id_surat')->limit(5)->get()->map(static fn (Surat $surat): array => [
                'number' => $surat->nomor_surat,
                'type' => $surat->jenisSurat?->nama_jenis ?? 'Jenis surat tidak tersedia',
                'resident' => $surat->warga?->nama_lengkap ?? 'Warga tidak tersedia',
                'date' => $surat->tanggal_surat->translatedFormat('d M Y'),
                'status' => $surat->status,
            ])->all(),
            'categories' => $categories,
            'categoryTotal' => collect($categories)->sum('value'),
            'activities' => collect(),
        ];
    }

    /**
     * Menghasilkan kategori resmi untuk dashboard operator.
     *
     * @param  Builder<Surat>  $query  Builder surat desa operator.
     * @return array<int, array<string, int|string>> Empat kategori dashboard.
     */
    private function operatorCategories(Builder $query): array
    {
        $official = [
            'Surat Kematian' => ['label' => 'Kematian', 'tone' => 'people'],
            'Surat Kelahiran' => ['label' => 'Kelahiran', 'tone' => 'business'],
            'Surat Keterangan Umum' => ['label' => 'Keterangan Umum', 'tone' => 'welfare'],
            'Surat Pindah' => ['label' => 'Pindah', 'tone' => 'other'],
        ];
        $counts = (clone $query)->join('tbmaster_kategori_surat', 'tbtr_surat.kategori_surat_id', '=', 'tbmaster_kategori_surat.id_kategori_surat')->whereIn('tbmaster_kategori_surat.nama_kategori', array_keys($official))->selectRaw('tbmaster_kategori_surat.nama_kategori, COUNT(*) AS total')->groupBy('tbmaster_kategori_surat.nama_kategori')->pluck('total', 'nama_kategori');

        return collect($official)->map(static fn (array $category, string $name): array => ['name' => $category['label'], 'value' => (int) $counts->get($name, 0), 'tone' => $category['tone']])->values()->all();
    }
}
