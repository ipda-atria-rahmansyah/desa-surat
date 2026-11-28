<?php

namespace App\Http\Controllers\Pengaturan;

use App\Http\Controllers\Controller;
use App\Http\Requests\Pengaturan\UpdateDesaRequest;
use App\Models\Desa;
use App\Support\Activity\ActivityRecorder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Throwable;

/**
 * Menangani tampilan dan perubahan informasi desa pada modul Pengaturan Admin.
 *
 * Record desa dipilih secara deterministik berdasarkan ID terkecil sesuai
 * keputusan M16 dan M19, sehingga frontend tidak perlu mengirim id_desa.
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

    /**
     * Menyimpan perubahan data desa dari modal Admin.
     *
     * File baru disimpan terlebih dahulu pada disk public. Database diperbarui
     * dalam transaksi, kemudian logo lama yang berada di folder terkontrol
     * dihapus setelah transaksi berhasil. Jika database gagal, file baru
     * dibersihkan agar tidak meninggalkan file yatim.
     *
     * @param  UpdateDesaRequest  $request  Data desa yang sudah tervalidasi.
     * @return RedirectResponse Kembali ke halaman Data Desa dengan status operasi.
     */
    public function update(UpdateDesaRequest $request, ActivityRecorder $activityRecorder): RedirectResponse
    {
        $desa = Desa::query()->orderBy('id_desa')->first();

        if (! $desa) {
            return to_route('settings.data-desa')
                ->with('error', 'Data desa belum tersedia sehingga tidak dapat diperbarui.');
        }

        $validated = $request->validated();
        $newLogoPath = null;
        $oldLogoPath = $desa->logo;

        try {
            if ($request->hasFile('logo')) {
                $newLogoPath = $this->storeLogo($request);
                $validated['logo'] = $newLogoPath;
            }

            DB::transaction(function () use ($desa, $validated): void {
                $desa->update($validated);
            });
            $activityRecorder->record('UPDATE', 'Pengaturan Desa', 'Data desa berhasil diperbarui.', $desa);

            if ($newLogoPath !== null && $this->isManagedLogoPath($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }
        } catch (Throwable $exception) {
            if ($newLogoPath !== null) {
                Storage::disk('public')->delete($newLogoPath);
            }

            report($exception);

            return to_route('settings.data-desa')
                ->with('error', 'Data desa gagal diperbarui. Silakan coba lagi.');
        }

        return to_route('settings.data-desa')
            ->with('success', 'Data desa berhasil diperbarui.');
    }

    /**
     * Menyimpan logo baru pada folder public yang khusus untuk logo desa.
     *
     * @param  UpdateDesaRequest  $request  Request berisi UploadedFile tervalidasi.
     * @return string Path relatif logo pada disk public.
     */
    private function storeLogo(UpdateDesaRequest $request): string
    {
        return $request->file('logo')->store('desa/logo', 'public');
    }

    /**
     * Memastikan penghapusan hanya menyentuh path logo yang dibuat aplikasi.
     *
     * @param  string|null  $path  Path lama dari database.
     * @return bool True jika path berada di folder logo desa yang dikenal.
     */
    private function isManagedLogoPath(?string $path): bool
    {
        return is_string($path) && str_starts_with($path, 'desa/logo/');
    }
}
