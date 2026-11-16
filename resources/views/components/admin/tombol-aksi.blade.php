{{--
    Tombol aksi yang mengirim form ke route admin. Selama route belum dibuat, tombol tampil
    dengan gaya yang sama tetapi nonaktif, supaya tata letak tidak berubah saat backend tersambung.
    $konfirmasi: pesan di dialog konfirmasi sebelum form dikirim, untuk aksi yang tidak bisa dibatalkan.
    $judulKonfirmasi dan $tombolKonfirmasi: judul dialog dan label tombol lanjut (bawaannya teks tombol ini).
    Varian tolak tampil sebagai dialog bahaya (merah).
--}}
@props(['route', 'parameter' => [], 'varian' => 'utama', 'metode' => 'POST', 'konfirmasi' => null, 'judulKonfirmasi' => null, 'tombolKonfirmasi' => null])

@php
    $tersedia = Route::has($route);

    $kelas = match ($varian) {
        'setuju' => 'bg-status-success text-white hover:bg-lencana-hijau',
        'tolak' => 'border border-status-danger/70 bg-white text-status-danger hover:bg-lencana-merah-latar',
        default => 'bg-primary-container text-white hover:bg-primary',
    };
@endphp

<form
    method="POST"
    @if ($tersedia) action="{{ route($route, $parameter) }}" @endif
    @if ($konfirmasi)
        data-konfirmasi="{{ $konfirmasi }}"
        data-konfirmasi-varian="{{ $varian === 'tolak' ? 'bahaya' : 'biasa' }}"
        @if ($judulKonfirmasi) data-konfirmasi-judul="{{ $judulKonfirmasi }}" @endif
        @if ($tombolKonfirmasi) data-konfirmasi-tombol="{{ $tombolKonfirmasi }}" @endif
    @endif
    {{ $attributes->only('class') }}
>
    @csrf
    @if (strtoupper($metode) !== 'POST')
        @method($metode)
    @endif
    <button
        type="submit"
        @disabled(! $tersedia)
        @if (! $tersedia) title="Belum tersedia" @endif
        {{ $attributes->except('class')->class(['flex min-h-10 w-full items-center justify-center gap-1.5 rounded-xl px-5 text-[14px] leading-5 font-semibold whitespace-nowrap transition-colors duration-200 disabled:cursor-not-allowed', $kelas]) }}
    >
        {{ $slot }}
    </button>
</form>
