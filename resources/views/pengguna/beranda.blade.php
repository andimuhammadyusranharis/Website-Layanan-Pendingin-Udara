<x-layouts.pengguna title="Beranda">
    {{-- Variabel: $galeri, $ringkasanUlasan, $layananUnggulan, $produkUnggulan, $ulasan --}}
    @php
        $langkahPemesanan = [
            ['nomor' => '01', 'label' => 'Langkah pertama', 'judul' => 'Pilih Layanan & Masalah AC', 'ikon' => 'langkah-1', 'deskripsi' => 'Pilih kebutuhan perawatan: cuci higienis antibakteri, isi/tambah freon, instalasi unit baru, atau perbaikan kompresor bocor.'],
            ['nomor' => '02', 'label' => 'Langkah kedua', 'judul' => 'Tentukan Jadwal & Alamat', 'ikon' => 'langkah-2', 'deskripsi' => 'Tentukan tanggal dan sesi waktu kedatangan fleksibel (Pagi, Siang, Sore). Cantumkan alamat atau patokan jelas di Pangkep.'],
            ['nomor' => '03', 'label' => 'Langkah terakhir', 'judul' => 'Servis Tuntas & Bergaransi', 'ikon' => 'langkah-3', 'deskripsi' => 'Teknisi bekerja bersih tanpa ceceran kotor. Selesai uji dingin, bayar aman (Tunai/QRIS) dan nikmati jaminan garansi 14 hari.'],
        ];

        $warnaInisial = [
            'bg-primary-fixed text-primary',
            'bg-secondary-fixed text-secondary',
            'bg-tertiary-fixed text-tertiary',
        ];

        $inisial = fn (string $nama): string => Str::of($nama)
            ->replaceMatches('/^(Ibu|Bu|Bapak|Pak)\s+/i', '')
            ->explode(' ')
            ->take(2)
            ->map(fn (string $kata): string => Str::upper(Str::substr($kata, 0, 1)))
            ->implode('');
    @endphp

    {{-- Hero --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-10 lg:px-8 lg:pt-10 lg:pb-16">
        <div class="relative overflow-hidden rounded-3xl bg-white p-6 shadow-[0px_20px_25px_-5px_rgba(0,75,176,0.05),0px_8px_10px_-6px_rgba(0,75,176,0.05)] sm:p-10 lg:p-14">
            <div class="absolute -top-24 -right-24 size-96 rounded-full bg-primary-fixed/30 blur-[32px]" aria-hidden="true"></div>
            <div class="absolute -bottom-20 -left-20 size-80 rounded-full bg-tertiary-fixed/30 blur-[32px]" aria-hidden="true"></div>

            <div class="relative grid grid-cols-1 items-center gap-10 lg:grid-cols-12 lg:gap-12">
                <div class="flex flex-col gap-6 lg:col-span-7">
                    <h1 class="text-[32px] leading-10 font-bold tracking-[-0.8px] text-on-surface lg:text-[42px] lg:leading-[52px] lg:tracking-[-1.05px]">
                        <span class="text-primary-container">Service AC Rapi</span> &amp; Cepat Datang ke Rumah Anda
                    </h1>

                    <p class="max-w-[512px] text-body-md text-text-secondary lg:text-[18px] lg:leading-[29.25px]">
                        Solusi cuci higienis, perbaikan kebocoran, tambah freon, dan instalasi AC dengan teknisi berpengalaman serta bergaransi resmi.
                    </p>

                    <div class="flex flex-col gap-3 pt-2 sm:flex-row sm:items-center sm:gap-4">
                        <a
                            href="{{ route('layanan.index') }}"
                            class="flex min-h-[52px] items-center justify-center gap-2 rounded-full bg-primary px-7 text-label-lg text-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-primary-container"
                        >
                            Pesan Layanan Sekarang
                            <img src="{{ asset('images/beranda-ikon-panah-putih.svg') }}" alt="" />
                        </a>
                        <a
                            href="{{ route('layanan.index') }}"
                            class="flex min-h-[52px] items-center justify-center gap-2 rounded-full bg-surface-container-low px-6 text-label-lg text-primary transition-colors duration-200 hover:bg-surface-tint"
                        >
                            <img src="{{ asset('images/beranda-ikon-kalkulator.svg') }}" alt="" />
                            Hitung Biaya
                        </a>
                    </div>

                    @if ($ringkasanUlasan['jumlah'] > 0)
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 border-t border-border-subtle pt-[17px]">
                            <span class="flex items-center" aria-hidden="true">
                                @for ($i = 0; $i < 5; $i++)
                                    <img src="{{ asset('images/beranda-ikon-bintang.svg') }}" alt="" />
                                @endfor
                            </span>
                            <span class="text-label-md text-on-surface">{{ number_format($ringkasanUlasan['rata_rata'], 1) }} / 5.0</span>
                            <span class="text-body-md text-text-secondary">• {{ $ringkasanUlasan['jumlah'] }}+ Ulasan Warga Pangkep</span>
                        </div>
                    @endif
                </div>

                <div class="lg:col-span-5">
                    @if (count($galeri) > 0)
                        <div
                            data-galeri
                            class="relative mx-auto aspect-[432/324] w-full max-w-[460px] overflow-hidden rounded-3xl border border-border-subtle bg-surface-container shadow-[0px_25px_50px_-12px_rgba(0,0,0,0.25)]"
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
                                <p class="shrink-0 rounded-full bg-black/40 px-2.5 py-1 text-[12px] leading-[18px] text-white/80 backdrop-blur-[6px]">
                                    <span data-galeri-nomor>1</span>/{{ count($galeri) }}
                                </p>
                            </div>

                            @if (count($galeri) > 1)
                                <button
                                    type="button"
                                    data-galeri-sebelumnya
                                    aria-label="Gambar sebelumnya"
                                    class="absolute top-1/2 left-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] backdrop-blur-[2px] transition-colors duration-200 hover:bg-white"
                                >
                                    <img src="{{ asset('images/beranda-ikon-galeri-sebelumnya.svg') }}" alt="" />
                                </button>
                                <button
                                    type="button"
                                    data-galeri-selanjutnya
                                    aria-label="Gambar selanjutnya"
                                    class="absolute top-1/2 right-2 flex size-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/80 shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] backdrop-blur-[2px] transition-colors duration-200 hover:bg-white"
                                >
                                    <img src="{{ asset('images/beranda-ikon-galeri-selanjutnya.svg') }}" alt="" />
                                </button>
                            @endif
                        </div>
                    @else
                        <div class="mx-auto flex aspect-[432/324] w-full max-w-[460px] items-center justify-center rounded-3xl border border-border-subtle bg-surface-container p-6 text-center text-body-md text-text-secondary">
                            Foto pekerjaan belum tersedia.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Layanan unggulan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col gap-8 px-4 pb-14 lg:gap-10 lg:px-8 lg:pt-8 lg:pb-20">
        <div class="flex flex-col gap-1.5">
            <p class="text-[13px] leading-[18px] font-bold tracking-[1.3px] text-primary uppercase">Pilihan servis</p>
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Layanan Sesuai Kebutuhan Anda</h2>
        </div>

        @if (count($layananUnggulan) > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($layananUnggulan as $layanan)
                    <a
                        href="{{ route('layanan.index') }}"
                        class="group flex flex-col justify-between overflow-hidden rounded-2xl bg-white drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)] transition-shadow duration-200 hover:shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.12)]"
                    >
                        <div class="flex flex-col gap-2 p-6 pb-4">
                            <span class="flex size-14 items-center justify-center rounded-2xl bg-surface-container-low text-primary">
                                <span class="material-symbols-outlined text-[32px]" aria-hidden="true">{{ $layanan['ikon'] }}</span>
                            </span>
                            <h3 class="pt-3 text-headline-sm text-on-surface">{{ $layanan['nama'] }}</h3>
                            <p class="text-body-md text-text-secondary">{{ $layanan['deskripsi'] }}</p>
                        </div>
                        <div class="flex items-center justify-between gap-3 bg-surface-container-low px-6 pt-4 pb-6">
                            <p class="flex flex-col">
                                <span class="text-label-sm text-text-secondary">Mulai dari</span>
                                <span class="text-headline-sm text-primary-container">Rp{{ number_format($layanan['harga_mulai'], 0, ',', '.') }}</span>
                            </p>
                            <span class="flex size-10 shrink-0 items-center justify-center rounded-full bg-white transition-colors duration-200 group-hover:bg-surface-tint">
                                <img src="{{ asset('images/beranda-ikon-panah-biru.svg') }}" alt="" />
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-border-subtle bg-white p-6 text-body-md text-text-secondary">Daftar layanan belum tersedia.</p>
        @endif
    </section>

    {{-- Cara memesan --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-14 lg:px-8 lg:pb-20">
        <div class="relative flex flex-col items-center gap-8 overflow-hidden rounded-3xl border border-border-subtle bg-white p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] sm:p-10 lg:gap-10 lg:p-14">
            <div class="absolute -top-20 -right-20 size-80 rounded-full bg-primary-fixed/20 blur-[32px]" aria-hidden="true"></div>
            <div class="absolute -bottom-20 -left-20 size-72 rounded-full bg-tertiary-fixed/20 blur-[32px]" aria-hidden="true"></div>

            <div class="relative flex max-w-[672px] flex-col items-center gap-2.5 text-center">
                <p class="flex items-center gap-1.5 rounded-full bg-primary-fixed/40 px-3 py-1 text-[13px] leading-[18px] font-bold tracking-[1.3px] text-primary uppercase">
                    <img src="{{ asset('images/beranda-ikon-alur.svg') }}" alt="" />
                    Alur pemesanan praktis
                </p>
                <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg lg:tracking-[-0.9px]">3 Langkah Mudah Servis AC Anda</h2>
                <p class="text-body-md text-text-secondary">
                    Tanpa repot, teknisi profesional {{ config('usaha.nama') }} siap tiba di hunian atau ruko Anda di Pangkep dengan perlengkapan higienis komplit.
                </p>
            </div>

            <ol class="relative grid w-full grid-cols-1 gap-6 lg:grid-cols-3 lg:gap-8">
                @foreach ($langkahPemesanan as $langkah)
                    <li class="flex flex-col rounded-2xl border border-border-subtle bg-surface-container-low/70 p-6 shadow-[0px_1px_2px_0px_rgba(0,0,0,0.05)] lg:p-[29px]">
                        <div class="flex items-center justify-between pb-5">
                            <span class="flex size-11 items-center justify-center rounded-xl bg-primary text-headline-sm font-bold text-white shadow-[0px_4px_6px_-1px_rgba(0,75,176,0.2),0px_2px_4px_-2px_rgba(0,75,176,0.2)]">{{ $langkah['nomor'] }}</span>
                            <span class="flex size-10 items-center justify-center rounded-xl bg-primary-fixed/50">
                                <img src="{{ asset('images/beranda-ikon-'.$langkah['ikon'].'.svg') }}" alt="" />
                            </span>
                        </div>
                        <div class="flex flex-1 flex-col gap-1 pb-6">
                            <p class="text-[12px] leading-[18px] font-bold tracking-[0.6px] text-primary uppercase">{{ $langkah['label'] }}</p>
                            <h3 class="text-[20px] leading-[25px] font-bold text-on-surface">{{ $langkah['judul'] }}</h3>
                            <p class="pt-1.5 text-body-md text-text-secondary">{{ $langkah['deskripsi'] }}</p>
                        </div>
                        <div class="border-t border-border-subtle/80" aria-hidden="true"></div>
                    </li>
                @endforeach
            </ol>

            <div class="relative flex w-full flex-col gap-5 rounded-2xl border border-border-subtle bg-surface-container-low/60 p-6 lg:flex-row lg:items-center lg:justify-between lg:p-[29px]">
                <div class="flex max-w-[672px] items-start gap-4">
                    <span class="flex size-10 shrink-0 items-center justify-center rounded-xl bg-primary-fixed">
                        <img src="{{ asset('images/beranda-ikon-fleksibel.svg') }}" alt="" />
                    </span>
                    <div class="flex flex-col">
                        <h3 class="text-label-lg font-bold text-on-surface">Fleksibel &amp; Transparan</h3>
                        <p class="text-body-md text-text-secondary">Jadwal servis dapat diubah atau dibatalkan tanpa biaya tambahan hingga 12 jam sebelum waktu kunjungan teknisi.</p>
                    </div>
                </div>
                <div class="flex shrink-0 flex-col gap-3 sm:flex-row sm:items-center">
                    <a
                        href="{{ config('usaha.whatsapp.tautan') }}"
                        target="_blank"
                        rel="noopener"
                        class="flex min-h-[46px] items-center justify-center gap-2 rounded-full border border-whatsapp/40 bg-whatsapp/20 px-[21px] text-[14px] leading-5 font-semibold text-text-primary transition-colors duration-200 hover:bg-whatsapp/30"
                    >
                        <img src="{{ asset('images/beranda-ikon-konsultasi.svg') }}" alt="" />
                        Konsultasi WA
                    </a>
                    <a
                        href="{{ route('layanan.index') }}"
                        class="flex min-h-[46px] items-center justify-center gap-2 rounded-full bg-primary px-6 text-label-md text-white shadow-[0px_4px_6px_-1px_rgba(0,0,0,0.1),0px_2px_4px_-2px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-primary-container"
                    >
                        Mulai Pesan Sekarang
                        <img src="{{ asset('images/beranda-ikon-panah-putih-kecil.svg') }}" alt="" />
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Produk unggulan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col gap-8 px-4 pb-14 lg:px-8 lg:pb-20">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div class="flex flex-col gap-1.5 pt-1">
                <p class="text-[13px] leading-[18px] font-bold tracking-[1.3px] text-primary uppercase">Gudang sparepart Pangkep</p>
                <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Suku Cadang &amp; Komponen Ready</h2>
            </div>
            <a href="{{ route('toko.index') }}" class="flex min-h-11 shrink-0 items-center gap-1 text-label-md text-primary hover:underline">
                Lihat Semua Katalog Komponen
                <img src="{{ asset('images/beranda-ikon-panah-tautan.svg') }}" alt="" />
            </a>
        </div>

        @if (count($produkUnggulan) > 0)
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                @foreach ($produkUnggulan as $produk)
                    <article class="flex flex-col rounded-2xl bg-white p-5 drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)]">
                        <div class="relative aspect-square overflow-hidden rounded-xl bg-surface-container-low">
                            <img src="{{ asset($produk['gambar']) }}" alt="{{ $produk['nama'] }}" loading="lazy" class="size-full object-cover" />
                            <span
                                @class([
                                    'absolute top-2.5 left-2.5 rounded-full px-2.5 py-0.5 text-label-sm text-white',
                                    'bg-status-warning' => $produk['stok'] <= 3,
                                    'bg-status-success' => $produk['stok'] > 3,
                                ])
                            >{{ $produk['stok'] <= 3 ? 'Stok Menipis' : 'Stok Tersedia' }}</span>
                        </div>
                        <div class="flex flex-1 flex-col pt-4">
                            <p class="text-label-sm text-text-secondary">{{ $produk['kategori'] }}</p>
                            <h3 class="pt-1 pb-2 text-headline-sm text-on-surface">{{ $produk['nama'] }}</h3>
                            <p class="mt-auto pb-4 text-body-md font-bold text-primary">Rp{{ number_format($produk['harga'], 0, ',', '.') }}</p>
                            <a
                                href="{{ route('toko.index') }}"
                                aria-label="Tambah {{ $produk['nama'] }} ke keranjang"
                                class="flex min-h-11 items-center justify-center gap-2 rounded-full bg-surface-container-low px-3 text-label-md text-on-surface transition-colors duration-200 hover:bg-surface-tint"
                            >
                                <img src="{{ asset('images/beranda-ikon-keranjang.svg') }}" alt="" />
                                Tambah
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <p class="rounded-2xl border border-border-subtle bg-white p-6 text-body-md text-text-secondary">Produk unggulan belum tersedia.</p>
        @endif
    </section>

    {{-- Ulasan pelanggan --}}
    <section class="mx-auto flex w-full max-w-[1280px] flex-col items-center gap-8 px-4 pb-14 lg:gap-10 lg:px-8 lg:pb-20">
        <div class="flex max-w-[576px] flex-col items-center gap-1.5 pt-1 text-center">
            <p class="text-[13px] leading-[18px] font-bold tracking-[1.3px] text-primary uppercase">Kepuasan pelanggan</p>
            <h2 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Ulasan Nyata Warga Pangkep</h2>
            <p class="pt-0.5 text-body-md text-text-secondary">Dedikasi teknisi lokal kami menjaga kesejukan rumah, toko, dan instansi di Kabupaten Pangkajene.</p>
        </div>

        @if (count($ulasan) > 0)
            <div class="grid w-full grid-cols-1 items-start gap-6 md:grid-cols-2 lg:grid-cols-3">
                @foreach ($ulasan as $item)
                    <figure class="flex flex-col overflow-hidden rounded-2xl bg-white drop-shadow-[0px_1px_1px_rgba(0,0,0,0.05)]">
                        <div class="flex flex-col gap-3 p-6">
                            <p class="flex items-center">
                                @for ($i = 0; $i < $item['rating']; $i++)
                                    <img src="{{ asset('images/beranda-ikon-bintang.svg') }}" alt="" />
                                @endfor
                                <span class="sr-only">Rating {{ $item['rating'] }} dari 5</span>
                            </p>
                            <blockquote class="text-body-md text-on-surface italic">"{{ $item['komentar'] }}"</blockquote>
                        </div>
                        <figcaption class="flex items-center gap-3 bg-surface-container-low p-4">
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

    {{-- Ajakan hitung estimasi --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-12 lg:px-8 lg:pb-16">
        <div class="relative overflow-hidden rounded-3xl bg-primary-container p-6 shadow-[0px_20px_25px_-5px_rgba(30,99,214,0.2),0px_8px_10px_-6px_rgba(30,99,214,0.2)] sm:p-10 lg:p-14">
            <div class="absolute -top-20 -right-20 size-80 rounded-full bg-white/10 blur-[20px]" aria-hidden="true"></div>
            <div class="absolute -bottom-10 -left-10 size-60 rounded-full bg-tertiary-fixed/20 blur-[12px]" aria-hidden="true"></div>

            <div class="relative flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                <div class="flex max-w-[586px] flex-col items-start gap-2">
                    <p class="rounded-full bg-white/20 px-3.5 py-1 text-label-sm font-bold text-white">Siap Melayani Hari Ini</p>
                    <h2 class="pt-1 text-headline-lg-mobile text-white lg:text-[38px] lg:leading-[46px]">Butuh Servis AC Cepat Hari Ini di Pangkep?</h2>
                    <p class="text-body-md text-on-primary-container lg:text-body-lg">Dapatkan estimasi biaya transparan tanpa biaya tersembunyi. Jadwal fleksibel, teknisi profesional terpercaya langsung meluncur ke tempat Anda.</p>
                </div>
                <div class="flex shrink-0 flex-col gap-3 sm:flex-row sm:items-center">
                    <a
                        href="{{ route('layanan.index') }}"
                        class="flex min-h-14 items-center justify-center gap-2 rounded-full bg-white px-8 text-label-lg font-bold text-primary shadow-[0px_10px_15px_-3px_rgba(0,0,0,0.1),0px_4px_6px_-4px_rgba(0,0,0,0.1)] transition-colors duration-200 hover:bg-surface-tint"
                    >
                        <img src="{{ asset('images/beranda-ikon-kalkulator-besar.svg') }}" alt="" />
                        Hitung Estimasi Biaya
                    </a>
                    <a
                        href="{{ route('layanan.index') }}"
                        class="flex min-h-14 items-center justify-center gap-2 rounded-full bg-primary px-8 text-label-lg text-white transition-colors duration-200 hover:bg-on-primary-fixed-variant"
                    >
                        Pesan Teknisi
                        <img src="{{ asset('images/beranda-ikon-panah-putih-panjang.svg') }}" alt="" />
                    </a>
                </div>
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
