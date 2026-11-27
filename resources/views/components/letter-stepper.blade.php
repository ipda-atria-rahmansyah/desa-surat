@props(['current' => 1])

<div class="app-stepper mb-4" aria-label="Tahapan pembuatan surat">
    @foreach ([['Pilih Kategori', 1], ['Pilih Jenis Surat', 2], ['Cari Warga', 3], ['Isi Data Surat', 4], ['Selesai', 5]] as $index => $item)
        @php($status = $item[1] < $current ? 'done' : ($item[1] === $current ? 'active' : 'pending'))
        <div class="app-step {{ $status }}">
            <div class="app-step-top">
                @if ($index >= 0)
                    <span class="app-step-line {{ $item[1] <= $current ? 'done' : '' }}"></span>
                @endif
                <span class="app-step-circle">
                    {!! $status === 'done' ? '<i class="bi bi-check-lg"></i>' : $item[1] !!}
                </span>
                @if ($index <= 4)
                    <span class="app-step-line {{ $item[1] < $current ? 'done' : '' }}"></span>
                @endif
            </div>
            <span class="app-step-label {{ $item[1] === $current ? '' : 'd-none d-md-block' }}">{{ $item[0] }}</span>
        </div>
    @endforeach
</div>
