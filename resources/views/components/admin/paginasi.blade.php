{{-- Keterangan jumlah data dan navigasi halaman. $data adalah LengthAwarePaginator. --}}
@props(['data', 'keterangan'])

@php
    $awal = max(1, min($data->currentPage() - 2, $data->lastPage() - 4));
    $akhir = min($data->lastPage(), $awal + 4);
    $kelasKotak = 'flex size-10 items-center justify-center rounded-xl border text-[14px] leading-5 font-semibold transition-colors duration-200';
@endphp

<div {{ $attributes->class(['flex flex-col gap-3 pt-4 sm:flex-row sm:items-center sm:justify-between']) }}>
    <p class="text-[14px] leading-5 text-text-secondary lg:text-[15px] lg:leading-6">{{ $keterangan }}</p>

    @if ($data->hasPages())
        <nav aria-label="Halaman" class="flex items-center gap-2">
            @if ($data->onFirstPage())
                <span class="{{ $kelasKotak }} border-border-subtle text-outline-variant" aria-hidden="true">
                    <span class="material-symbols-outlined text-[20px]">chevron_left</span>
                </span>
            @else
                <a href="{{ $data->previousPageUrl() }}" rel="prev" aria-label="Halaman sebelumnya" class="{{ $kelasKotak }} border-border-subtle bg-white text-text-primary hover:border-primary-container">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chevron_left</span>
                </a>
            @endif

            @foreach ($data->getUrlRange($awal, $akhir) as $nomor => $url)
                @if ($nomor === $data->currentPage())
                    <span aria-current="page" class="{{ $kelasKotak }} border-primary-container bg-primary-container text-white">{{ $nomor }}</span>
                @else
                    <a href="{{ $url }}" aria-label="Halaman {{ $nomor }}" class="{{ $kelasKotak }} border-border-subtle bg-white text-text-primary hover:border-primary-container">{{ $nomor }}</a>
                @endif
            @endforeach

            @if ($data->hasMorePages())
                <a href="{{ $data->nextPageUrl() }}" rel="next" aria-label="Halaman berikutnya" class="{{ $kelasKotak }} border-border-subtle bg-white text-text-primary hover:border-primary-container">
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chevron_right</span>
                </a>
            @else
                <span class="{{ $kelasKotak }} border-border-subtle text-outline-variant" aria-hidden="true">
                    <span class="material-symbols-outlined text-[20px]">chevron_right</span>
                </span>
            @endif
        </nav>
    @endif
</div>
