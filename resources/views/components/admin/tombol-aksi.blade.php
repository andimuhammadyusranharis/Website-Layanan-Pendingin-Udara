{{--
    Tombol aksi yang mengirim form POST ke route admin. Selama route belum dibuat, tombol tampil
    dengan gaya yang sama tetapi nonaktif, supaya tata letak tidak berubah saat backend tersambung.
--}}
@props(['route', 'parameter' => [], 'varian' => 'utama'])

@php
    $tersedia = Route::has($route);

    $kelas = match ($varian) {
        'setuju' => 'bg-status-success text-white hover:bg-lencana-hijau',
        'tolak' => 'border border-border-subtle bg-white text-status-danger hover:border-status-danger hover:bg-lencana-merah-latar',
        default => 'bg-primary-container text-white hover:bg-primary',
    };
@endphp

<form method="POST" @if ($tersedia) action="{{ route($route, $parameter) }}" @endif {{ $attributes->only('class') }}>
    @csrf
    <button
        type="submit"
        @disabled(! $tersedia)
        @if (! $tersedia) title="Belum tersedia" @endif
        {{ $attributes->except('class')->class(['flex min-h-10 w-full items-center justify-center gap-1.5 rounded-xl px-5 text-[14px] leading-5 font-semibold transition-colors duration-200 disabled:cursor-not-allowed', $kelas]) }}
    >
        {{ $slot }}
    </button>
</form>
