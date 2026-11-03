@php
    $tautanLayanan = [
        ['label' => 'Layanan dan tarif', 'route' => 'layanan.index'],
        ['label' => 'Suku cadang dan unit AC', 'route' => 'toko.index'],
        ['label' => 'Ulasan pelanggan', 'route' => 'ulasan.index'],
        ['label' => 'Tentang kami dan kontak', 'route' => 'kontak.index'],
    ];

    $tautanAkun = collect([
        ['label' => 'Hitung estimasi biaya', 'route' => 'estimasi'],
        ['label' => 'Cek status pesanan', 'route' => 'akun.pesanan.index'],
        ['label' => 'Kebijakan privasi', 'route' => 'kebijakan-privasi'],
        ['label' => 'Syarat dan ketentuan', 'route' => 'syarat'],
    ])->filter(fn (array $item): bool => Route::has($item['route']));
@endphp

<footer {{ $attributes->merge(['class' => 'w-full shrink-0 bg-surface-dark pt-12 pb-8']) }}>
    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-10 px-4 lg:px-8">
        <div class="grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-col">
                <div class="flex items-center gap-2.5 pb-4">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-lg bg-white">
                        <img src="{{ asset('images/komponen-logo.png') }}" alt="" width="32" height="32" class="size-8" />
                    </span>
                    <span class="text-[20px] leading-7 font-bold text-white">{{ config('usaha.nama') }}</span>
                </div>
                <p class="pb-5 text-body-md text-surface-variant/90">{{ config('usaha.deskripsi') }}</p>
                <ul class="flex flex-col gap-2 text-[14px] leading-5 text-surface-container-high/85">
                    <li class="flex items-start gap-2">
                        <img src="{{ asset('images/komponen-ikon-lokasi.svg') }}" alt="" class="shrink-0" />
                        {{ config('usaha.alamat') }}
                    </li>
                    <li class="flex items-center gap-2">
                        <img src="{{ asset('images/komponen-ikon-jam.svg') }}" alt="" class="shrink-0" />
                        {{ config('usaha.jam_buka') }}
                    </li>
                </ul>
            </div>

            <nav class="flex flex-col gap-4" aria-labelledby="footer-halaman">
                <h2 id="footer-halaman" class="text-headline-sm text-white">Halaman</h2>
                <ul class="flex flex-col gap-3">
                    @foreach ($tautanLayanan as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="text-[14px] leading-5 text-surface-variant/90 hover:text-white hover:underline">{{ $item['label'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            @if ($tautanAkun->isNotEmpty())
                <nav class="flex flex-col gap-4" aria-labelledby="footer-bantuan">
                    <h2 id="footer-bantuan" class="text-headline-sm text-white">Bantuan</h2>
                    <ul class="flex flex-col gap-3">
                        @foreach ($tautanAkun as $item)
                            <li>
                                <a href="{{ route($item['route']) }}" class="text-[14px] leading-5 text-surface-variant/90 hover:text-white hover:underline">{{ $item['label'] }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            @endif

            <div class="flex flex-col gap-4">
                <h2 class="text-headline-sm text-white">Kontak dan pembayaran</h2>
                <div class="flex flex-col gap-3.5">
                    <a
                        href="{{ config('usaha.whatsapp.tautan') }}"
                        target="_blank"
                        rel="noopener"
                        class="flex items-center gap-2 self-start rounded-xl border border-whatsapp/40 bg-whatsapp/20 px-[15px] py-[9px] text-[14px] leading-5 text-white"
                    >
                        <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" class="shrink-0" />
                        WhatsApp {{ config('usaha.whatsapp.nomor') }}
                    </a>
                    <div class="flex flex-col gap-1.5 text-[14px] leading-5">
                        <p class="font-semibold text-white">Metode Pembayaran:</p>
                        <ul class="list-disc pl-5 text-surface-variant/90">
                            <li>Tunai, dibayar setelah pekerjaan selesai</li>
                            <li>Transfer bank, bukti diunggah di halaman pesanan</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <p class="border-t border-surface-variant/15 pt-6 text-[13px] leading-5 text-surface-variant/80">© {{ date('Y') }} {{ config('usaha.nama') }}, Kabupaten Pangkajene dan Kepulauan.</p>
    </div>
</footer>
