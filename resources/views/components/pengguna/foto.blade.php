@props(['src' => null, 'alt' => '', 'ikon' => 'image'])

{{-- Foto dengan cadangan: bila berkas belum ada di public/, tampil panel ikon agar tata letak tidak bergeser. --}}
@if ($src && is_file(public_path($src)))
    <img src="{{ asset($src) }}" alt="{{ $alt }}" loading="lazy" {{ $attributes->merge(['class' => 'size-full object-cover']) }} />
@else
    <div {{ $attributes->merge(['class' => 'flex size-full flex-col items-center justify-center gap-1 bg-surface-tint text-primary-container']) }} role="img" aria-label="{{ $alt ?: 'Foto belum tersedia' }}">
        <span class="material-symbols-outlined text-[40px]" aria-hidden="true">{{ $ikon }}</span>
        <span class="text-label-sm text-text-secondary">Foto menyusul</span>
    </div>
@endif
