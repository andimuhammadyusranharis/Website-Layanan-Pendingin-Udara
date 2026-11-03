@props(['stok', 'satuan' => null, 'batasMenipis' => 3])

<span
    {{
        $attributes->class([
            'inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-label-sm',
            'bg-lencana-merah-latar text-lencana-merah' => $stok <= 0,
            'bg-lencana-kuning-latar text-lencana-kuning' => $stok > 0 && $stok <= $batasMenipis,
            'bg-lencana-hijau-latar text-lencana-hijau' => $stok > $batasMenipis,
        ])
    }}
>
    <span class="size-2 rounded-full bg-current" aria-hidden="true"></span>
    @if ($stok <= 0)
        Stok habis
    @elseif ($stok <= $batasMenipis)
        Sisa {{ $stok }} {{ $satuan }}
    @else
        Stok {{ $stok }} {{ $satuan }}
    @endif
</span>
