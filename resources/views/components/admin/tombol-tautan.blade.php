{{--
    Tautan berbentuk tombol. Bila route belum dibuat, tautan tetap tampil tetapi tidak bisa diklik.
    Varian: utama (pil biru untuk aksi utama halaman), garis (putih bergaris, untuk Ubah dan Detail).
--}}
@props(['route', 'parameter' => [], 'varian' => 'garis', 'ikon' => null])

@php
    $tersedia = Route::has($route);
@endphp

<a
    @if ($tersedia) href="{{ route($route, $parameter) }}" @else aria-disabled="true" title="Belum tersedia" @endif
    {{
        $attributes->class([
            'inline-flex items-center justify-center gap-2 text-[14px] leading-5 font-semibold whitespace-nowrap transition-colors duration-200',
            'min-h-11 rounded-full bg-primary-container px-5 text-white hover:bg-primary hover:shadow-[0px_8px_20px_rgba(30,99,214,0.25)]' => $varian === 'utama',
            'min-h-10 rounded-xl border border-border-subtle bg-white px-4 text-on-secondary-fixed-variant hover:border-primary-container hover:bg-surface-tint' => $varian === 'garis',
            'cursor-not-allowed' => ! $tersedia,
        ])
    }}
>
    @if ($ikon)
        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">{{ $ikon }}</span>
    @endif
    {{ $slot }}
</a>
