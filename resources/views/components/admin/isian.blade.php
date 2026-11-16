{{--
    Kolom isian form admin: label, kontrol (input, textarea, atau select), bantuan, dan galat validasi dari server.
    $nilai: nilai awal (data tersimpan). Nilai lama dari form yang gagal divalidasi diutamakan lewat old().
    $opsi: untuk tipe select, array nilai => label.
--}}
@props(['nama', 'label', 'tipe' => 'text', 'nilai' => null, 'wajib' => false, 'bantuan' => null, 'opsi' => [], 'baris' => 4])

@php
    $id = 'kolom-'.$nama;
    $adaGalat = $errors->has($nama);
    $idKeterangan = $adaGalat ? $id.'-galat' : ($bantuan ? $id.'-bantuan' : null);
    $nilaiTampil = old($nama, $nilai);

    $kelas = [
        'w-full rounded-xl border bg-surface-container-low px-4 text-[16px] leading-6 text-text-primary transition-colors duration-200 focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none',
        'h-12' => $tipe !== 'textarea',
        'py-3' => $tipe === 'textarea',
        'border-status-danger focus:border-status-danger' => $adaGalat,
        'border-transparent focus:border-primary-container' => ! $adaGalat,
    ];
@endphp

<div {{ $attributes->class(['flex flex-col gap-2']) }}>
    <label for="{{ $id }}" class="text-[15px] leading-6 font-semibold text-text-primary">
        {{ $label }}
        @if ($wajib)
            <span class="text-status-danger" aria-hidden="true">*</span>
        @endif
    </label>

    @if ($tipe === 'textarea')
        <textarea
            id="{{ $id }}"
            name="{{ $nama }}"
            rows="{{ $baris }}"
            @required($wajib)
            @if ($adaGalat) aria-invalid="true" @endif
            @if ($idKeterangan) aria-describedby="{{ $idKeterangan }}" @endif
            @class($kelas)
        >{{ is_array($nilaiTampil) ? implode("\n", $nilaiTampil) : $nilaiTampil }}</textarea>
    @elseif ($tipe === 'select')
        <select
            id="{{ $id }}"
            name="{{ $nama }}"
            @required($wajib)
            @if ($adaGalat) aria-invalid="true" @endif
            @if ($idKeterangan) aria-describedby="{{ $idKeterangan }}" @endif
            @class($kelas)
        >
            <option value="">Pilih...</option>
            @foreach ($opsi as $nilaiOpsi => $labelOpsi)
                <option value="{{ $nilaiOpsi }}" @selected((string) $nilaiTampil === (string) $nilaiOpsi)>{{ $labelOpsi }}</option>
            @endforeach
        </select>
    @else
        <input
            id="{{ $id }}"
            name="{{ $nama }}"
            type="{{ $tipe }}"
            value="{{ $nilaiTampil }}"
            @if ($tipe === 'number') min="0" inputmode="numeric" @endif
            @required($wajib)
            @if ($adaGalat) aria-invalid="true" @endif
            @if ($idKeterangan) aria-describedby="{{ $idKeterangan }}" @endif
            @class($kelas)
        />
    @endif

    @if ($adaGalat)
        <p id="{{ $id }}-galat" class="flex items-start gap-1.5 text-[14px] leading-5 text-status-danger">
            <span class="material-symbols-outlined text-[18px]" aria-hidden="true">error</span>
            {{ $errors->first($nama) }}
        </p>
    @elseif ($bantuan)
        <p id="{{ $id }}-bantuan" class="text-[14px] leading-5 text-text-secondary">{{ $bantuan }}</p>
    @endif
</div>
