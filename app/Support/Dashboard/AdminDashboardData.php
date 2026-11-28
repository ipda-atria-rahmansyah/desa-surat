<?php

namespace App\Support\Dashboard;

use App\Models\ActivityLog;
use App\Models\JenisSurat;
use App\Models\KategoriSurat;
use App\Models\Surat;
use App\Models\User;
use App\Models\Warga;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

/**
 * Menyediakan seluruh data agregat Dashboard Admin dari database.
 *
 * Admin memakai scope seluruh desa lokal. Karena surat tidak menyimpan desa_id,
 * scope surat diturunkan melalui relasi warga dan tidak pernah berasal dari
 * parameter klien, sesuai alur-sistem.md bagian 7.
 */
class AdminDashboardData
{
    /** @var array<string, array{label: string, tone: string}> */
    private const OFFICIAL_CATEGORIES = [
        'Surat Kematian' => ['label' => 'Kematian', 'tone' => 'people'],
        'Surat Kelahiran' => ['label' => 'Kelahiran', 'tone' => 'business'],
        'Surat Keterangan Umum' => ['label' => 'Keterangan Umum', 'tone' => 'welfare'],
        'Surat Pindah' => ['label' => 'Pindah', 'tone' => 'other'],
    ];

    /**
     * Mengambil paket data dashboard admin tanpa menerima scope dari klien.
     *
     * @return array<string, mixed> Data statistik, tabel, dan grafik admin.
     */
    public function all(): array
    {
        $surat = $this->suratSeluruhDesa();
        $categories = $this->categoryStatistics($surat);

        return [
            'statistics' => $this->statistics($surat),
            'letters' => $this->latestLetters($surat),
            'categories' => $categories,
            'categoryTotal' => collect($categories)->sum('value'),
            'activities' => $this->latestActivities(),
        ];
    }

    /**
     * Membuat builder surat yang hanya menerima warga dengan desa valid.
     *
     * @return Builder<Surat> Builder surat seluruh desa lokal.
     */
    private function suratSeluruhDesa(): Builder
    {
        return Surat::query()->whereHas('warga', static fn (Builder $query): Builder => $query->whereNotNull('desa_id'));
    }

    /**
     * Menghitung enam metrik admin dengan agregasi database.
     *
     * @param  Builder<Surat>  $surat  Builder surat yang sudah terscope.
     * @return array<int, array<string, int|string>> Kartu statistik dashboard.
     */
    private function statistics(Builder $surat): array
    {
        return [
            ['label' => 'Total Warga Aktif', 'value' => Warga::query()->whereNotNull('desa_id')->where('is_active', true)->count(), 'note' => 'Warga aktif seluruh desa', 'icon' => 'bi-people-fill', 'tone' => 'blue'],
            ['label' => 'Total Surat', 'value' => (clone $surat)->count(), 'note' => 'Semua status surat', 'icon' => 'bi-file-earmark-text-fill', 'tone' => 'green'],
            ['label' => 'Total Surat Selesai', 'value' => (clone $surat)->where('status', 'selesai')->count(), 'note' => 'Status selesai', 'icon' => 'bi-check-circle-fill', 'tone' => 'orange'],
            ['label' => 'Total Kategori Surat', 'value' => $this->officialCategoryQuery()->count(), 'note' => 'Empat kategori resmi', 'icon' => 'bi-grid-3x3-gap-fill', 'tone' => 'purple'],
            ['label' => 'Total Jenis Surat', 'value' => $this->officialJenisQuery()->where('is_active', true)->count(), 'note' => 'Jenis aktif kategori resmi', 'icon' => 'bi-files', 'tone' => 'blue'],
            ['label' => 'Total Pengguna Aktif', 'value' => User::query()->where('is_active', true)->whereIn('role', ['admin', 'operator'])->count(), 'note' => 'Admin dan operator aktif', 'icon' => 'bi-person-check-fill', 'tone' => 'green'],
        ];
    }

    /**
     * Mengambil lima surat terbaru lintas operator dengan relasi yang dirender.
     *
     * @param  Builder<Surat>  $query  Builder surat seluruh desa.
     * @return Collection<int, Surat> Surat terbaru eager-loaded.
     */
    private function latestLetters(Builder $query): Collection
    {
        return $query
            ->with([
                'warga:id_warga,nama_lengkap',
                'jenisSurat:id_jenis_surat,nama_jenis',
                'pembuat:id_user,name,role',
            ])
            ->latest('tanggal_surat')
            ->latest('id_surat')
            ->limit(5)
            ->get();
    }

    /**
     * Menghasilkan empat kategori resmi meskipun nilai agregatnya nol.
     *
     * @param  Builder<Surat>  $query  Builder surat seluruh desa.
     * @return array<int, array{name: string, value: int, tone: string}> Data grafik.
     */
    private function categoryStatistics(Builder $query): array
    {
        $counts = (clone $query)
            ->join('tbmaster_kategori_surat', 'tbtr_surat.kategori_surat_id', '=', 'tbmaster_kategori_surat.id_kategori_surat')
            ->whereIn('tbmaster_kategori_surat.nama_kategori', array_keys(self::OFFICIAL_CATEGORIES))
            ->selectRaw('tbmaster_kategori_surat.nama_kategori, COUNT(*) AS total')
            ->groupBy('tbmaster_kategori_surat.nama_kategori')
            ->pluck('total', 'nama_kategori')
            ->map(static fn (mixed $total): int => (int) $total);

        return collect(self::OFFICIAL_CATEGORIES)
            ->map(static fn (array $category, string $name): array => [
                'name' => $category['label'],
                'value' => $counts->get($name, 0),
                'tone' => $category['tone'],
            ])
            ->values()
            ->all();
    }

    /**
     * Mengambil lima aktivitas terbaru dan eager-load pengguna pelakunya.
     *
     * @return Collection<int, ActivityLog> Aktivitas terbaru lintas pengguna.
     */
    private function latestActivities(): Collection
    {
        return ActivityLog::query()
            ->with('user:id_user,name,role')
            ->latest('created_at')
            ->latest('id_activity_log')
            ->limit(5)
            ->get();
    }

    /**
     * Builder kategori yang whitelist empat kategori RAD.
     *
     * @return Builder<KategoriSurat> Builder kategori resmi.
     */
    private function officialCategoryQuery(): Builder
    {
        return KategoriSurat::query()->whereIn('nama_kategori', array_keys(self::OFFICIAL_CATEGORIES));
    }

    /**
     * Builder jenis surat aktif yang berada pada kategori resmi.
     *
     * @return Builder<JenisSurat> Builder jenis resmi.
     */
    private function officialJenisQuery(): Builder
    {
        return JenisSurat::query()->whereHas(
            'kategoriSurat',
            static fn (Builder $query): Builder => $query->whereIn('nama_kategori', array_keys(self::OFFICIAL_CATEGORIES)),
        );
    }
}
