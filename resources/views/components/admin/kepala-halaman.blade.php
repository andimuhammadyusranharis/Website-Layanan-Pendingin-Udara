@props(['judul', 'deskripsi' => null])

<section {{ $attributes->class(['flex flex-col gap-4 rounded-2xl border border-border-subtle bg-white p-5 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06),0px_4px_6px_-2px_rgba(20,64,127,0.03)] sm:flex-row sm:items-center sm:justify-between']) }}>
    <div class="flex flex-col gap-1">
        <h1 class="text-[20px] leading-7 font-bold text-on-surface lg:text-[24px] lg:leading-8">{{ $judul }}</h1>
        @if ($deskripsi)
            <p class="text-[14px] leading-5 text-text-secondary lg:text-[15px] lg:leading-6">{{ $deskripsi }}</p>
        @endif
    </div>
    @isset($aksi)
        <div class="flex flex-wrap gap-3">{{ $aksi }}</div>
    @endisset
</section>
