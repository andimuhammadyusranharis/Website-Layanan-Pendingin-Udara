{{-- Lencana status berbentuk pil dengan titik. Warna: netral, biru, kuning, sian, hijau, merah. --}}
@props(['warna' => 'netral'])

<span
    {{
        $attributes->class([
            'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[13px] leading-[18px] font-semibold whitespace-nowrap',
            'bg-lencana-netral-latar text-lencana-netral' => $warna === 'netral',
            'bg-lencana-biru-latar text-lencana-biru' => $warna === 'biru',
            'bg-lencana-kuning-latar text-lencana-kuning' => $warna === 'kuning',
            'bg-lencana-sian-latar text-lencana-sian' => $warna === 'sian',
            'bg-lencana-hijau-latar text-lencana-hijau' => $warna === 'hijau',
            'bg-lencana-merah-latar text-lencana-merah' => $warna === 'merah',
        ])
    }}
>
    <span class="size-2 rounded-full bg-current" aria-hidden="true"></span>
    {{ $slot }}
</span>
