@php
    $layananUtama = [
        'Cuci AC Higienis',
        'Perbaikan Bocor & Kerusakan',
        'Pasang & Bongkar Unit',
        'Isi & Tambah Freon Murni',
    ];

    $bantuan = [
        ['label' => 'Pesan Jadwal Teknisi', 'route' => 'layanan.index', 'ikon' => 'panah-kanan'],
        ['label' => 'Cek Status & Antrean Pesanan', 'route' => 'login', 'ikon' => 'panah-kanan'],
        ['label' => 'Kalkulator Estimasi Biaya', 'route' => 'layanan.index', 'ikon' => 'panah-kanan'],
        ['label' => 'Kebijakan Garansi 14 Hari', 'route' => 'kontak.index', 'ikon' => 'garansi'],
    ];
@endphp

<footer {{ $attributes->merge(['class' => 'w-full shrink-0 bg-[linear-gradient(145deg,#09142b_0%,#0f1f44_50%,#162a5c_100%)] pt-16 pb-10']) }}>
    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-14 px-4 lg:px-8">
        <div class="grid grid-cols-1 gap-12 sm:grid-cols-2 lg:grid-cols-4">
            <div class="flex flex-col">
                <div class="flex items-center gap-2.5 pb-4">
                    <span class="flex h-8 w-[135.42px] shrink-0 items-center justify-center rounded-[4px] bg-white">
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

            <div class="flex flex-col gap-4">
                <h2 class="text-headline-sm text-white">Layanan Utama</h2>
                <ul class="flex flex-col gap-3">
                    @foreach ($layananUtama as $label)
                        <li>
                            <a href="{{ route('layanan.index') }}" class="flex items-center gap-1.5 text-[14px] leading-5 text-surface-variant/90 hover:text-white">
                                <img src="{{ asset('images/komponen-ikon-panah-kanan.svg') }}" alt="" class="shrink-0" />
                                {{ $label }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex flex-col gap-4">
                <h2 class="text-headline-sm text-white">Bantuan &amp; Panduan</h2>
                <ul class="flex flex-col gap-3">
                    @foreach ($bantuan as $item)
                        <li>
                            <a href="{{ route($item['route']) }}" class="flex items-center gap-1.5 text-[14px] leading-5 text-surface-variant/90 hover:text-white">
                                @if ($item['ikon'] === 'garansi')
                                    <img src="{{ asset('images/komponen-ikon-garansi.svg') }}" alt="" class="shrink-0" />
                                @else
                                    <img src="{{ asset('images/komponen-ikon-panah-kanan.svg') }}" alt="" class="shrink-0" />
                                @endif
                                {{ $item['label'] }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="flex flex-col gap-4">
                <h2 class="text-headline-sm text-white">Kontak &amp; Bayar</h2>
                <div class="flex flex-col gap-3.5">
                    <a
                        href="{{ config('usaha.whatsapp.tautan') }}"
                        target="_blank"
                        rel="noopener"
                        class="flex items-center gap-2 rounded-xl border border-whatsapp/40 bg-whatsapp/20 px-[15px] py-[9px] text-[14px] leading-5 text-white"
                    >
                        <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" class="shrink-0" />
                        WhatsApp: {{ config('usaha.whatsapp.nomor') }}
                    </a>
                    <div class="flex flex-col gap-1.5 text-[14px] leading-5">
                        <p class="font-semibold text-white">Metode Pembayaran:</p>
                        <p class="text-surface-variant/90">• Bayar di Tempat (COD Teknisi)</p>
                        <p class="text-surface-variant/90">• QRIS &amp; Transfer Bank Resmi</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col gap-2 border-t border-surface-variant/15 pt-[25px] text-[12px] leading-4 text-surface-variant/70 lg:flex-row lg:items-center lg:justify-between">
            <p>© {{ date('Y') }} {{ config('usaha.nama') }} Pangkep. Hak Cipta Dilindungi Undang-Undang.</p>
            <p>Layanan Servis Pendingin Terpercaya di Kabupaten Pangkajene.</p>
        </div>
    </div>
</footer>
