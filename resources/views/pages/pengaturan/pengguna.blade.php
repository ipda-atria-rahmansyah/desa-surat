{{--
    Halaman daftar pengguna untuk Pengaturan Admin.
    Dipakai oleh route settings.pengguna dan menerima collection $users
    berisi atribut non-sensitif User serta tiga angka ringkasan.
    Variabel $users berupa paginator; $search dan $selectedRole berisi filter
    aktif untuk mempertahankan nilai form dan pagination.
--}}
@extends('layouts.app')

@section('content')
<div id="pengguna" class="app-pengaturan-page">
    @if (session('success'))
        <div class="alert alert-success" role="alert">{{ session('success') }}</div>
    @endif

    <div class="mb-4">
        <h1 class="app-page-title fw-bold mb-2">Pengaturan Pengguna</h1>
        <p class="app-description mb-0">Kelola akun pengguna yang dapat mengakses sistem.</p>
    </div>

    <div class="row row-cols-1 row-cols-sm-3 g-3 mb-4">
        <div class="col">
            <div class="card app-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="app-stat-icon app-stat-icon--green d-flex align-items-center justify-content-center"><i class="bi bi-people-fill"></i></span>
                    <div><div class="app-stat-label">Total Pengguna</div><div class="app-stat-value fw-bold">{{ $totalUsers }}</div></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card app-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="app-stat-icon app-stat-icon--blue d-flex align-items-center justify-content-center"><i class="bi bi-shield-fill-check"></i></span>
                    <div><div class="app-stat-label">Operator</div><div class="app-stat-value fw-bold">{{ $totalOperators }}</div></div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card app-card h-100">
                <div class="card-body d-flex align-items-center gap-3">
                    <span class="app-stat-icon app-stat-icon--purple d-flex align-items-center justify-content-center"><i class="bi bi-person-fill-gear"></i></span>
                    <div><div class="app-stat-label">Administrator</div><div class="app-stat-value fw-bold">{{ $totalAdmins }}</div></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card app-card mb-4">
        <div class="card-body">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i class="bi bi-people-fill text-primary fs-5"></i>
                <h2 class="app-card-title fw-semibold mb-0">Daftar Pengguna</h2>
            </div>

            <form class="row g-2 mb-3 d-flex align-items-center" method="get" action="{{ route('settings.pengguna') }}" novalidate>
                <div class="col-12 col-md">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input class="form-control" type="search" name="q" value="{{ $search }}" placeholder="Cari nama pengguna..." aria-label="Cari nama pengguna" maxlength="100" autocomplete="off">
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <select class="form-select" name="role" aria-label="Filter role">
                        <option value="" @selected($selectedRole === '')>Semua Role</option>
                        <option value="admin" @selected($selectedRole === 'admin')>Administrator</option>
                        <option value="operator" @selected($selectedRole === 'operator')>Operator</option>
                    </select>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-grid d-sm-flex gap-2">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search me-1"></i>Filter</button>
                        <a class="btn btn-outline-secondary" href="{{ route('settings.pengguna') }}">Reset Filter</a>
                        <a class="btn btn-success" href="{{ route('settings.pengguna.create') }}"><i class="bi bi-plus-lg me-1"></i>Tambah Pengguna</a>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table app-table align-middle mb-0">
                    <thead>
                        <tr><th scope="col">No</th><th scope="col">Nama Pengguna</th><th scope="col">Username</th><th scope="col">Role</th><th scope="col">Status</th><th scope="col" class="text-center">Aksi</th></tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr>
                                <td>{{ $users->firstItem() + $loop->index }}</td>
                                <td class="fw-semibold">{{ $user->name }}</td>
                                <td>{{ $user->username }}</td>
                                <td><span class="badge rounded-pill {{ $user->role === 'admin' ? 'bg-success-subtle text-success-emphasis' : 'bg-primary-subtle text-primary-emphasis' }}">{{ $user->role === 'admin' ? 'Administrator' : 'Operator' }}</span></td>
                                <td><span class="badge rounded-pill {{ $user->is_active ? 'bg-success-subtle text-success-emphasis' : 'bg-secondary-subtle text-secondary-emphasis' }}">{{ $user->is_active ? 'Aktif' : 'Tidak Aktif' }}</span></td>
                                <td class="text-center">
                                    <div class="d-inline-flex gap-1">
                                        <button class="btn btn-sm btn-outline-primary" type="button" disabled aria-label="Ubah pengguna"><i class="bi bi-pencil-fill"></i></button>
                                        <button class="btn btn-sm btn-outline-danger" type="button" disabled aria-label="Hapus pengguna"><i class="bi bi-trash-fill"></i></button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td class="text-center text-body-secondary py-4" colspan="6">Belum ada pengguna.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3 pt-3">
                <small class="text-body-secondary">Menampilkan {{ $users->firstItem() ?? 0 }}–{{ $users->lastItem() ?? 0 }} dari {{ $users->total() }} data</small>
                @if ($users->hasPages())
                    {{ $users->onEachSide(1)->links('pagination::bootstrap-5') }}
                @endif
            </div>
        </div>
    </div>

    <div class="card app-card" id="ubah-password">
        <div class="card-body d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-3">
            <div><h2 class="app-card-title fw-semibold mb-1">Ubah Password</h2><p class="app-description mb-0">Anda dapat mengubah password akun Anda secara berkala untuk keamanan yang lebih baik.</p></div>
            <button class="btn btn-primary flex-shrink-0" type="button" disabled><i class="bi bi-lock-fill me-1"></i>Ubah Password</button>
        </div>
    </div>
</div>
@endsection
