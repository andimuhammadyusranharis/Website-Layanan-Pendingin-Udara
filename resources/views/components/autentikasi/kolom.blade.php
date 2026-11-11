{{--
    Kolom isian form masuk dan daftar: label, ikon, galat validasi dari server, dan tombol
    tampilkan kata sandi bila $tipe="password".
--}}
@props(['nama', 'label', 'ikon', 'tipe' => 'text', 'wajib' => false, 'tandaWajib' => true, 'bantuan' => null, 'nilaiLama' => true])

@php
    $id = 'kolom-'.$nama;
    $adaGalat = $errors->has($nama);
    $idKeterangan = $adaGalat ? $id.'-galat' : ($bantuan ? $id.'-bantuan' : null);
@endphp

<div class="flex flex-col gap-2">
    @isset($labelKanan)
        <div class="flex items-center justify-between gap-3">
            <label for="{{ $id }}" class="text-[15px] leading-6 font-semibold text-text-primary">{{ $label }}</label>
            {{ $labelKanan }}
        </div>
    @else
        <label for="{{ $id }}" class="text-[15px] leading-6 font-semibold text-text-primary">
            {{ $label }}
            @if ($wajib && $tandaWajib)
                <span class="text-status-danger" aria-hidden="true">*</span>
            @endif
        </label>
    @endisset

    <div class="relative flex items-center">
        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[22px] text-text-secondary" aria-hidden="true">{{ $ikon }}</span>
        <input
            id="{{ $id }}"
            name="{{ $nama }}"
            type="{{ $tipe }}"
            @if ($nilaiLama && $tipe !== 'password') value="{{ old($nama) }}" @endif
            @required($wajib)
            @if ($adaGalat) aria-invalid="true" @endif
            @if ($idKeterangan) aria-describedby="{{ $idKeterangan }}" @endif
            {{
                $attributes->class([
                    'h-12 w-full rounded-xl border bg-surface-container-low pl-11 text-[16px] leading-6 text-text-primary transition-colors duration-200 placeholder:text-text-secondary/70 focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none',
                    'pr-12' => $tipe === 'password',
                    'pr-4' => $tipe !== 'password',
                    'border-status-danger focus:border-status-danger' => $adaGalat,
                    'border-transparent focus:border-primary-container' => ! $adaGalat,
                ])
            }}
        />
        @if ($tipe === 'password')
            <button
                type="button"
                data-lihat-sandi="{{ $id }}"
                aria-label="Tampilkan kata sandi"
                aria-pressed="false"
                class="absolute right-1.5 flex size-10 items-center justify-center rounded-lg text-text-secondary transition-colors duration-200 hover:text-text-primary"
            >
                <span class="material-symbols-outlined text-[22px]" aria-hidden="true">visibility</span>
            </button>
        @endif
    </div>

    {{ $slot }}

    @if ($adaGalat)
        <p id="{{ $id }}-galat" class="flex items-start gap-1.5 text-[14px] leading-5 text-status-danger">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">error</span>
            {{ $errors->first($nama) }}
        </p>
    @elseif ($bantuan)
        <p id="{{ $id }}-bantuan" class="text-[14px] leading-5 text-text-secondary">{{ $bantuan }}</p>
    @endif
</div>
