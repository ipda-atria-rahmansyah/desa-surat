<div class="app-stepper d-flex align-items-start mb-4" aria-label="Tahapan pembuatan surat">
    @foreach ([['Pilih Kategori', 'done'], ['Pilih Jenis Surat', 'done'], ['Cari Warga', 'done'], ['Isi Data Surat', 'active'], ['Selesai', 'pending']] as $index => $item)
        <div class="app-step {{ $item[1] }} d-flex flex-column align-items-center">
            <div class="app-step-top d-flex align-items-center w-100">
                @if ($index > 0)<span class="app-step-line {{ $index <= 3 ? 'done' : '' }}"></span>@endif
                <span class="app-step-circle">{!! $item[1] === 'done' ? '<i class="bi bi-check-lg"></i>' : $index + 1 !!}</span>
                @if ($index < 4)<span class="app-step-line {{ $index < 3 ? 'done' : '' }}"></span>@endif
            </div>
            <span class="app-step-label {{ $index === 3 ? '' : 'd-none d-md-block' }}">{{ $item[0] }}</span>
        </div>
    @endforeach
</div>