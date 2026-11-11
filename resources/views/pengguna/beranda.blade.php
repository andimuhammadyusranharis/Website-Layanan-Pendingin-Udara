<x-layouts.pengguna title="Beranda">
    {{-- Variabel: $galeri, $ringkasanUlasan, $layananUnggulan, $produkUnggulan, $ulasan --}}
    @php
        $langkahPemesanan = [
            ['nomor' => '1', 'judul' => 'Pilih layanan', 'ikon' => 'langkah-1', 'deskripsi' => 'Pilih cuci, perbaikan, isi freon, atau bongkar pasang. Tulis keluhan AC Anda bila ada.'],
            ['nomor' => '2', 'judul' => 'Tentukan jadwal dan alamat', 'ikon' => 'langkah-2', 'deskripsi' => 'Pilih tanggal dan sesi pagi, siang, atau sore. Tulis alamat dan patokan rumah di Pangkep.'],
            ['nomor' => '3', 'judul' => 'Teknisi datang dan bekerja', 'ikon' => 'langkah-3', 'deskripsi' => 'AC diuji sampai dingin sebelum teknisi pulang. Bayar tunai atau transfer setelah pekerjaan selesai.'],
        ];

        $tautanPesan = Route::has('pesan.create') ? route('pesan.create') : route('layanan.index');
        $tautanTarif = Route::has('estimasi') ? route('estimasi') : route('layanan.index');
        $labelTarif = Route::has('estimasi') ? 'Hitung estimasi biaya' : 'Lihat tarif layanan';

        $warnaInisial = [
            'bg-primary-fixed text-primary',
            'bg-secondary-fixed text-secondary',
            'bg-tertiary-fixed text-tertiary',
        ];

        $inisial = fn (string $nama): string => Str::of($nama)
            ->replaceMatches('/^(Ibu|Bu|Bapak|Pak|Hj\.|H\.|Drs\.)\s+/i', '')
            ->explode(' ')
            ->take(2)
            ->map(fn (string $kata): string => Str::upper(Str::substr($kata, 0, 1)))
            ->implode('');
    @endphp

    {{-- Hero --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-10 lg:px-8 lg:pt-8 lg:pb-12">
        <div class="overflow-hidden rounded-3xl bg-white p-6 shadow-[0px_20px_25px_-5px_rgba(0,75,176,0.05),0px_8px_10px_-6px_rgba(0,75,176,0.05)] sm:p-8 lg:p-10">
            <div class="grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-10">
                <div class="flex flex-col gap-5 lg:col-span-7">
                    <h1 class="text-[32px] leading-10 font-bold tracking-[-0.8px] text-on-surface lg:text-[38px] lg:leading-[46px] lg:tracking-[-0.95px]">
                        <span class="text-primary-container">Service AC Rapi,</span> Datang ke Rumah Anda
                    </h1>

                    <p class="max-w-[512px] text-body-md text-text-secondary lg:text-body-lg">
                        Cuci, perbaikan, isi freon, dan pemasangan AC. Teknisi datang ke rumah atau kantor Anda di Kabupaten Pangkep.
                    </p>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:gap-4">
                        <a
                            href="{{ $tautanPesan }}"
                            class="flex min-h-12 items-center justify-center rounded-full bg-primary px-7 text-label-lg text-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-primary-container"
                        >
                            Pesan layanan
                        </a>
                        <a
                            href="{{ $tautanTarif }}"
                            class="flex min-h-12 items-center justify-center gap-2 rounded-full bg-surface-container-low px-6 text-label-lg text-primary transition-colors duration-200 hover:bg-surface-tint"
                        >
                            <img src="{{ asset('images/beranda-ikon-kalkulator.svg') }}" alt="" />
                            {{ $labelTarif }}
                        </a>
                    </div>

                    @if ($ringkasanUlasan['jumlah'] > 0)
                        <a href="{{ route('ulasan.index') }}" class="flex min-h-11 flex-wrap items-center gap-x-3 gap-y-1 self-start border-t border-border-subtle pt-[17px] hover:underline">
                            <x-pengguna.bintang :nilai="$ringkasanUlasan['rata_rata']" />
                            <span class="text-label-md text-on-surface">{{ number_format($ringkasanUlasan['rata_rata'], 1, ',', '.') }} dari 5</span>
                            <span class="text-body-md text-text-secondary">{{ $ringkasanUlasan['jumlah'] }} ulasan pelanggan</span>
                        </a>
                    @endif
                </div>

                <div class="lg:col-span-5">
                    @if (count($galeri) > 0)
                        <div
                            data-galeri
                            class="relative mx-auto aspect-[432/324] w-full max-w-[420px] overflow-hidden rounded-3xl border border-border-subtle bg-surface-container shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.25)]"
                        >
                            @foreach ($galeri as $foto)
                                <img
                                    data-galeri-foto
                                    data-keterangan="{{ $foto['keterangan'] }}"
                                    src="{{ asset($foto['gambar']) }}"
                                    alt="{{ $foto['keterangan'] }}"
                                    @class(['absolute inset-0 size-full object-cover', 'hidden' => ! $loop->first])
                                />
                            @endforeach

                            <div class="absolute inset-x-0 bottom-0 flex items-end justify-between gap-3 bg-linear-to-t from-surface-dark/80 via-surface-dark/30 to-surface-dark/0 p-4 pt-8">
                                <p data-galeri-keterangan class="text-[14px] leading-5 font-semibold text-white">{{ $galeri[0]['keterangan'] }}</p>
                                <p class="shrink-0 rounded-full bg-black/40 px-2.5 py-1 text-[12px] leading-[18px] text-white/80">
                                    <span data-galeri-nomor>1</span>/{{ count($galeri) }}
                                </p>
                            </div>

                            @if (count($galeri) > 1)
                                <button
                                    type="button"
                                    data-galeri-sebelumnya
                                    aria-label="Gambar sebelumnya"
                                    class="absolute top-1/2 left-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-white"
                                >
                                    <img src="{{ asset('images/beranda-ikon-galeri-sebelumnya.svg') }}" alt="" />
                                </button>
                                <button
                                    type="button"
                                    data-galeri-selanjutnya
                                    aria-label="Gambar selanjutnya"
                                    class="absolute top-1/2 right-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-white"
                                >
                                    <img src="{{ asset('images/beranda-ikon-galeri-selanjutnya.svg') }}" alt="" />
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="mx-auto flex aspect-[432/324] w-full max-w-[420px] flex-col items-center justify-center gap-1 rounded-3xl border border-border-subtle bg-surface-tint p-6 text-center text-primary-container">
                            <span class="material-symbols-outlined text-[40px]" aria-hidden="true">photo_camera</span>
                            <span class="text-label-sm text-text-secondary">Foto hasil pekerjaan menyusul</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan unggulan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col gap-6 px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Layanan dan tarif</h2>
            <a href="{{ route('layanan.index') }}" class="flex min-h-11 shrink-0 items-center text-label-md text-primary hover:underline">Lihat semua layanan</a>
        </div>

        @if (count($layananUnggulan) > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5">
                @foreach ($layananUnggulan as $layanan)
                    <a
                        href="{{ route('layanan.index') }}"
                        class="flex flex-col justify-between overflow-hidden rounded-2xl bg-white drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)] transition-shadow duration-200 hover:shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.12)]"
                    >
                        <div class="flex flex-col gap-1.5 p-5 pb-4">
                            <span class="flex size-11 items-center justify-center rounded-xl bg-surface-container-low text-primary">
                                <span class="material-symbols-outlined text-[26px]" aria-hidden="true">{{ $layanan['ikon'] }}</span>
                            </span>
                            <h3 class="pt-2 text-headline-sm font-bold text-on-surface">{{ $layanan['nama'] }}</h3>
                            <p class="line-clamp-2 text-[14px] leading-5 text-text-secondary">{{ $layanan['deskripsi'] }}</p>
                        </div>
                        <p class="flex flex-col bg-surface-container-low px-5 py-3">
                            <span class="text-label-sm text-text-secondary">Mulai dari</span>
                            <span class="text-headline-sm text-primary-container">Rp{{ number_format($layanan['harga_mulai'], 0, ',', '.') }}</span>
                        </p>
                    </a>
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-border-subtle bg-white p-6 text-body-md text-text-secondary">Daftar layanan belum tersedia.</p>
        @endif
    </section>

    {{-- Cara memesan --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex flex-col gap-6 rounded-3xl border border-border-subtle bg-white p-5 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] sm:p-8">
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Cara memesan</h2>

            <ol class="grid w-full grid-cols-1 gap-4 lg:grid-cols-3 lg:gap-5">
                @foreach ($langkahPemesanan as $langkah)
                    <li class="flex flex-col gap-3 rounded-2xl border border-border-subtle bg-surface-container-low/70 p-5">
                        <div class="flex items-center justify-between">
                            <span class="flex size-10 items-center justify-center rounded-xl bg-primary text-label-lg font-bold text-white">{{ $langkah['nomor'] }}</span>
                            <span class="flex size-10 items-center justify-center rounded-xl bg-primary-fixed/50">
                                <img src="{{ asset('images/beranda-ikon-'.$langkah['ikon'].'.svg') }}" alt="" />
                            </span>
                        </div>
                        <h3 class="text-headline-sm font-bold text-on-surface">{{ $langkah['judul'] }}</h3>
                        <p class="text-body-md text-text-secondary">{{ $langkah['deskripsi'] }}</p>
                    </li>
                @endforeach
            </ol>

            <div class="flex items-start gap-4 rounded-2xl border border-border-subtle bg-surface-container-low/60 p-5">
                <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-fixed">
                    <img src="{{ asset('images/beranda-ikon-fleksibel.svg') }}" alt="" />
                </span>
                <div class="flex flex-col">
                    <h3 class="text-label-lg font-bold text-on-surface">Pembatalan pesanan</h3>
                    <p class="text-body-md text-text-secondary">Pesanan bisa dibatalkan paling lambat 12 jam sebelum jadwal kunjungan teknisi.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk unggulan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col gap-6 px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Suku cadang di toko</h2>
            <a href="{{ route('toko.index') }}" class="flex min-h-11 shrink-0 items-center text-label-md text-primary hover:underline">Lihat semua di toko</a>
        </div>

        @if (count($produkUnggulan) > 0)
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5">
                @foreach ($produkUnggulan as $produk)
                    <article class="flex flex-col rounded-2xl bg-white p-4 drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)]">
                        <div class="relative aspect-[16/10] overflow-hidden rounded-xl bg-surface-container-low">
                            <x-pengguna.foto :src="$produk['gambar']" :alt="$produk['nama']" ikon="inventory_2" />
                            <x-pengguna.stok :stok="$produk['stok']" class="absolute top-2.5 left-2.5" />
                        </div>
                        <div class="flex flex-1 flex-col pt-3">
                            <p class="text-label-sm text-text-secondary">{{ $produk['kategori'] }}</p>
                            <h3 class="pt-1 pb-2 text-body-semibold text-on-surface">{{ $produk['nama'] }}</h3>
                            <p class="mt-auto pb-3 text-headline-sm font-bold text-primary">Rp{{ number_format($produk['harga'], 0, ',', '.') }}</p>
                            <a
                                href="{{ route('toko.index') }}"
                                aria-label="Lihat {{ $produk['nama'] }} di toko"
                                class="flex min-h-11 items-center justify-center gap-2 rounded-full bg-surface-container-low px-3 text-label-md text-on-surface transition-colors duration-200 hover:bg-surface-tint"
                            >
                                <img src="{{ asset('images/beranda-ikon-keranjang.svg') }}" alt="" />
                                Lihat di toko
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-border-subtle bg-white p-6 text-body-md text-text-secondary">Produk belum tersedia.</p>
        @endif
    </section>

    {{-- Ulasan pelanggan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col gap-6 px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Ulasan pelanggan</h2>
            <a href="{{ route('ulasan.index') }}" class="flex min-h-11 shrink-0 items-center text-label-md text-primary hover:underline">Baca semua ulasan</a>
        </div>

        @if (count($ulasan) > 0)
            <div class="grid w-full grid-cols-1 items-start gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-5">
                @foreach ($ulasan as $item)
                    <figure class="flex flex-col overflow-hidden rounded-2xl bg-white drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col gap-2 p-5">
                            <x-pengguna.bintang :nilai="$item['rating']" />
                            <blockquote class="text-body-md text-on-surface">"{{ $item['komentar'] }}"</blockquote>
                        </div>
                        <figcaption class="flex items-center gap-3 bg-surface-container-low px-5 py-3">
                            <span @class(['flex size-10 shrink-0 items-center justify-center rounded-full text-label-lg font-bold', $warnaInisial[$loop->index % count($warnaInisial)]]) aria-hidden="true">{{ $inisial($item['nama']) }}</span>
                            <span class="flex flex-col">
                                <span class="text-label-md text-on-surface">{{ $item['nama'] }}</span>
                                <span class="text-label-sm text-text-secondary">{{ $item['lokasi'] }}</span>
                            </span>
                        </figcaption>
                    </figure>
                @endforeach
            </div>
        @else
            <p class="w-full rounded-2xl border border-border-subtle bg-white p-6 text-center text-body-md text-text-secondary">Belum ada ulasan dari pelanggan.</p>
        @endif
    </section>

    {{-- Ajakan bertanya --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12">
        <div class="flex flex-col gap-6 rounded-3xl bg-primary-container p-6 sm:p-8 lg:flex-row lg:items-center lg:justify-between lg:p-10">
            <div class="flex max-w-[586px] flex-col gap-2">
                <h2 class="text-headline-lg-mobile text-white">Belum yakin AC Anda kenapa?</h2>
                <p class="text-body-md text-on-primary-container">Ceritakan keluhannya atau kirim foto unit AC lewat WhatsApp. Kami bantu tentukan layanan yang cocok sebelum Anda memesan.</p>
            </div>
            <div class="flex shrink-0 flex-col gap-3 sm:flex-row sm:items-center">
                <a
                    href="{{ config('usaha.whatsapp.tautan') }}"
                    target="_blank"
                    rel="noopener"
                    class="flex min-h-12 items-center justify-center gap-2 rounded-full bg-white px-6 text-label-lg font-bold text-primary transition-colors duration-200 hover:bg-surface-tint"
                >
                    <img src="{{ asset('images/beranda-ikon-konsultasi.svg') }}" alt="" />
                    Tanya lewat WhatsApp
                </a>
                <a
                    href="{{ $tautanPesan }}"
                    class="flex min-h-12 items-center justify-center rounded-full bg-primary px-6 text-label-lg text-white transition-colors duration-200 hover:bg-on-primary-fixed-variant"
                >
                    Pesan layanan
                </a>
            </div>
        </div>
    </section>

    @push('scripts')
        <script>
            document.querySelectorAll('[data-galeri]').forEach((galeri) => {
                const foto = [...galeri.querySelectorAll('[data-galeri-foto]')];
                const keterangan = galeri.querySelector('[data-galeri-keterangan]');
                const nomor = galeri.querySelector('[data-galeri-nomor]');
                let aktif = 0;

                const tampilkan = (indeks) => {
                    aktif = (indeks + foto.length) % foto.length;
                    foto.forEach((item, i) => item.classList.toggle('hidden', i !== aktif));
                    keterangan.textContent = foto[aktif].dataset.keterangan;
                    nomor.textContent = aktif + 1;
                };

                galeri.querySelector('[data-galeri-sebelumnya]')?.addEventListener('click', () => tampilkan(aktif - 1));
                galeri.querySelector('[data-galeri-selanjutnya]')?.addEventListener('click', () => tampilkan(aktif + 1));
            });
        </script>
    @endpush
</x-layouts.pengguna>
