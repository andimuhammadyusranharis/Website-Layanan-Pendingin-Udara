{{-- Tab berbasis query string ?tab=. Kunci pertama di $tab adalah tab bawaan. --}}
@props(['tab', 'aktif', 'route'])

<nav {{ $attributes->class(['-mx-4 overflow-x-auto overflow-y-hidden px-4 sm:mx-0 sm:px-0']) }} aria-label="Bagian halaman">
    <ul class="flex min-w-max gap-6 border-b border-border-subtle sm:min-w-0">
        @foreach ($tab as $kunci => $label)
            @php($terpilih = $kunci === $aktif)
            <li>
                <a
                    href="{{ route($route, $kunci === array_key_first($tab) ? [] : ['tab' => $kunci]) }}"
                    @if ($terpilih) aria-current="page" @endif
                    @class([
                        '-mb-px flex min-h-11 items-center border-b-[3px] text-[15px] leading-6 font-semibold whitespace-nowrap transition-colors duration-200',
                        'border-primary-container text-primary-container' => $terpilih,
                        'border-transparent text-text-secondary hover:text-primary' => ! $terpilih,
                    ])
                >{{ $label }}</a>
            </li>
        @endforeach
    </ul>
</nav>
