{{--
    Form pencarian dan penyaring berbasis GET. Pilihan dropdown langsung dikirim saat diubah (lihat data-kirim-otomatis di app.js).
    $pilihan: list<array{nama: string, label: string, opsi: array<string, string>, semua?: string}>
    $nilai: nilai filter yang sedang dipakai, dengan kunci yang sama dengan nama field.
    $tetap: field tersembunyi yang ikut dikirim, misalnya tab yang sedang dibuka.
--}}
@props(['route', 'placeholder', 'pilihan' => [], 'nilai' => [], 'tetap' => []])

<form method="GET" action="{{ route($route) }}" role="search" {{ $attributes->class(['flex flex-col gap-3 lg:flex-row']) }}>
    @foreach ($tetap as $nama => $isi)
        <input type="hidden" name="{{ $nama }}" value="{{ $isi }}" />
    @endforeach

    <label class="relative flex-1">
        <span class="sr-only">{{ $placeholder }}</span>
        <span class="material-symbols-outlined pointer-events-none absolute top-1/2 left-3.5 -translate-y-1/2 text-[22px] text-text-secondary" aria-hidden="true">search</span>
        <input
            type="search"
            name="cari"
            value="{{ $nilai['cari'] ?? '' }}"
            placeholder="{{ $placeholder }}"
            class="h-11 w-full rounded-xl border border-border-subtle bg-white pr-4 pl-11 text-[15px] leading-6 text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
        />
    </label>

    @if (count($pilihan) > 0)
        <div class="grid grid-cols-1 gap-3 sm:grid-flow-col sm:auto-cols-fr lg:flex">
            @foreach ($pilihan as $item)
                <label class="relative lg:min-w-[150px]">
                    <span class="sr-only">{{ $item['label'] }}</span>
                    <select
                        name="{{ $item['nama'] }}"
                        data-kirim-otomatis
                        class="h-11 w-full cursor-pointer appearance-none rounded-xl border border-border-subtle bg-white pr-10 pl-4 text-[14px] leading-5 font-semibold text-text-primary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
                    >
                        @if (array_key_exists('semua', $item))
                            <option value="">{{ $item['label'] }}: {{ $item['semua'] }}</option>
                        @endif
                        @foreach ($item['opsi'] as $kunci => $label)
                            <option value="{{ $kunci }}" @selected(($nilai[$item['nama']] ?? '') === (string) $kunci)>{{ $item['label'] }}: {{ $label }}</option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined pointer-events-none absolute top-1/2 right-3 -translate-y-1/2 text-[20px] text-text-primary" aria-hidden="true">expand_more</span>
                </label>
            @endforeach
        </div>
    @endif

    <button type="submit" class="sr-only focus:not-sr-only focus:rounded-xl focus:bg-primary-container focus:px-4 focus:text-white">Terapkan</button>
</form>
