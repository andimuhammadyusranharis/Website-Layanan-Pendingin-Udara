@props(['nilai' => 0, 'ukuran' => 'text-[18px]'])

<span {{ $attributes->merge(['class' => 'inline-flex items-center']) }}>
    @for ($i = 1; $i <= 5; $i++)
        <span
            @class(['material-symbols-outlined', $ukuran, 'text-status-warning' => $i <= round($nilai), 'text-outline-variant' => $i > round($nilai)])
            style="font-variation-settings: 'FILL' 1"
            aria-hidden="true"
        >star</span>
    @endfor
    <span class="sr-only">Rating {{ $nilai }} dari 5</span>
</span>
