<x-layouts.pengguna :title="$halaman['judul']">
    {{-- Variabel: $halaman (judul, ringkasan, bagian, tautan_lain). Dipakai bersama oleh /kebijakan-privasi dan /syarat. --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-6 lg:px-8">
        <div class="flex max-w-[760px] flex-col gap-3">
            <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">{{ $halaman['judul'] }}</h1>
            <p class="text-body-md text-text-secondary lg:text-body-lg">{{ $halaman['ringkasan'] }}</p>
        </div>
    </section>

    <div class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex max-w-[760px] flex-col gap-5">
            @foreach ($halaman['bagian'] as $bagian)
                <section aria-labelledby="bagian-{{ $loop->iteration }}" class="rounded-3xl border border-border-subtle bg-white p-5 sm:p-6">
                    <h2 id="bagian-{{ $loop->iteration }}" class="text-headline-md text-on-surface">{{ $loop->iteration }}. {{ $bagian['judul'] }}</h2>

                    @if (! empty($bagian['paragraf']))
                        <p class="mt-3 text-body-md text-text-primary">{{ $bagian['paragraf'] }}</p>
                    @endif

                    @if (! empty($bagian['poin']))
                        <ul class="mt-3 flex list-disc flex-col gap-2 pl-5 text-body-md text-text-primary">
                            @foreach ($bagian['poin'] as $poin)
                                <li>{{ $poin }}</li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            @endforeach

            <p class="text-body-md text-text-secondary">
                Baca juga:
                <a href="{{ route($halaman['tautan_lain']['route']) }}" class="inline-flex min-h-11 items-center text-label-lg text-primary underline-offset-4 hover:underline">{{ $halaman['tautan_lain']['label'] }}</a>
            </p>
        </div>
    </div>
</x-layouts.pengguna>
