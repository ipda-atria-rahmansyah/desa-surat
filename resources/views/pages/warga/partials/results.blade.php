<div class="table-responsive">
    <table class="table app-warga-table align-middle mb-0">
        <thead><tr><th>No</th><th>NIK</th><th>Nama Lengkap</th><th>Alamat</th><th class="text-center">Aksi</th></tr></thead>
        <tbody>
            @forelse ($warga as $item)
                <tr><td>{{ $warga->firstItem() + $loop->index }}</td><td>{{ $item->nik }}</td><td>{{ $item->nama_lengkap }}</td><td>{{ $item->alamat }}</td><td><div class="d-flex justify-content-center gap-2"><a href="#" class="btn app-warga-action app-warga-action--view" aria-label="Lihat {{ $item->nama_lengkap }}"><i class="bi bi-eye-fill"></i></a><a href="#" class="btn app-warga-action app-warga-action--edit" aria-label="Ubah {{ $item->nama_lengkap }}"><i class="bi bi-pencil-fill"></i></a><a href="#" class="btn app-warga-action app-warga-action--delete" aria-label="Hapus {{ $item->nama_lengkap }}"><i class="bi bi-trash-fill"></i></a></div></td></tr>
            @empty
                <tr><td colspan="5" class="text-center">Data warga tidak ditemukan.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
<div class="app-warga-footer d-flex flex-column flex-sm-row align-items-sm-center justify-content-between gap-3">
    <span>Menampilkan {{ $warga->firstItem() ?? 0 }}–{{ $warga->lastItem() ?? 0 }} dari {{ $warga->total() }} data</span>
    {{ $warga->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>