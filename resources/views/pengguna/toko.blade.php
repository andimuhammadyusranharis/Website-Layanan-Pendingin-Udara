<x-layouts.pengguna title="Toko">
    {{-- Variabel: $kategoriProduk, $merekPopuler, $unitAc, $produk --}}
    @php
        $caraTerima = [
            ['ikon' => 'storefront', 'judul' => 'Ambil di toko', 'biaya' => 'Tanpa ongkir', 'deskripsi' => 'Datang ke '.config('usaha.alamat').' dan cek barangnya langsung sebelum dibawa pulang.', 'catatan' => config('usaha.jam_buka')],
            ['ikon' => 'two_wheeler', 'judul' => 'Diantar driver', 'biaya' => 'Ongkir dari Rp10.000', 'deskripsi' => 'Ongkir dihitung dari jarak toko ke alamat Anda: Rp10.000 untuk 3 km pertama, lalu Rp2.500 per km. Barang dikirim setelah pembayaran lunas.', 'catatan' => 'Hanya untuk wilayah Pangkep'],
            ['ikon' => 'engineering', 'judul' => 'Dibawa teknisi', 'biaya' => 'Tanpa ongkir', 'deskripsi' => 'Bila Anda juga memesan jasa servis atau pasang, barangnya dibawa teknisi saat datang ke rumah.', 'catatan' => 'Dipesan bersama layanan'],
        ];

        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $merekDisebut = fn (string $teks): string => collect($merekPopuler)
            ->filter(fn (string $merek): bool => Str::contains($teks, $merek, ignoreCase: true))
            ->map(fn (string $merek): string => Str::slug($merek))
            ->implode(' ');

        $kelasPilihan = 'flex min-h-11 items-center rounded-full border border-border-subtle bg-white px-4 text-label-md lg:min-h-10 lg:px-3.5 text-on-surface-variant transition-colors duration-200 hover:border-primary-container hover:text-primary aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container';
    @endphp

    <div data-saring>
        {{-- Judul dan pencarian --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-8 lg:px-8">
            <div class="flex max-w-[720px] flex-col gap-3">
                <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Suku cadang dan unit AC</h1>
                <p class="text-body-md text-text-secondary lg:text-body-lg">Komponen dan unit baru yang tersedia di toko kami. Stok yang tampil adalah stok saat ini.</p>
            </div>

            <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-border-subtle bg-white p-4 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] sm:p-5">
                <div class="flex flex-col gap-3 md:flex-row md:items-center">
                    <label class="relative flex flex-1 items-center">
                        <span class="sr-only">Cari barang</span>
                        <span class="material-symbols-outlined pointer-events-none absolute left-4 text-text-secondary" aria-hidden="true">search</span>
                        <input
                            type="search"
                            data-saring-cari
                            placeholder="Contoh: kapasitor, R32, 1 PK, Daikin"
                            class="h-12 w-full rounded-xl border border-border-subtle bg-surface-container-low pr-4 pl-12 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
                        />
                    </label>
                    <label class="flex items-center gap-3">
                        <span class="shrink-0 text-body-md text-text-secondary">Urutkan</span>
                        <select data-saring-urut class="h-12 w-full rounded-xl border border-border-subtle bg-surface-container-low px-3 text-body-md text-text-primary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none md:w-auto">
                            <option value="">Rekomendasi</option>
                            <option value="harga:asc">Harga termurah</option>
                            <option value="harga:desc">Harga termahal</option>
                        </select>
                    </label>
                </div>

                <div class="flex flex-wrap gap-2" role="group" aria-label="Saring menurut kategori">
                    <button type="button" data-saring-pilihan="kategori" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua ({{ count($unitAc) + count($produk) }})</button>
                    @foreach ($kategoriProduk as $kategori)
                        <button type="button" data-saring-pilihan="kategori" data-nilai="{{ $kategori['slug'] }}" aria-pressed="false" class="{{ $kelasPilihan }}">{{ $kategori['nama'] }}</button>
                    @endforeach
                </div>

                <div class="flex flex-wrap items-center gap-2 border-t border-border-subtle pt-3" role="group" aria-label="Saring menurut merek">
                    <span class="text-body-md text-text-secondary">Merek:</span>
                    <button type="button" data-saring-pilihan="merek" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua</button>
                    @foreach ($merekPopuler as $merek)
                        <button type="button" data-saring-pilihan="merek" data-nilai="{{ Str::slug($merek) }}" aria-pressed="false" class="{{ $kelasPilihan }}">{{ $merek }}</button>
                    @endforeach
                </div>
            </div>

            <p data-saring-status data-satuan="barang" role="status" class="mt-3 text-body-md text-text-secondary">{{ count($unitAc) + count($produk) }} barang ditampilkan</p>
        </section>

        {{-- Unit AC --}}
        <section data-saring-bagian class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8" aria-labelledby="judul-unit">
            <h2 id="judul-unit" class="mb-4 text-headline-md text-on-surface">Unit AC baru</h2>

            <div data-saring-daftar class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 lg:gap-5">
                @foreach ($unitAc as $ac)
                    <article
                        data-saring-item
                        data-kategori="{{ $ac['slug_kategori'] }}"
                        data-merek="{{ Str::slug($ac['merek']) }}"
                        data-harga="{{ $ac['harga'] }}"
                        data-teks="{{ Str::lower($ac['merek'].' '.$ac['model'].' '.$ac['nama'].' '.implode(' ', $ac['spesifikasi'])) }}"
                        class="flex flex-col overflow-hidden rounded-2xl border border-border-subtle bg-white"
                    >
                        <div class="relative aspect-[16/10] bg-surface-container-low">
                            <x-pengguna.foto :src="$ac['gambar']" :alt="$ac['merek'].' '.$ac['nama']" ikon="ac_unit" />
                            <x-pengguna.stok :stok="$ac['stok']" satuan="unit" class="absolute top-3 left-3" />
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <p class="text-label-sm text-primary">{{ $ac['merek'] }} <span class="font-semibold text-text-secondary">{{ $ac['model'] }}</span></p>
                            <h3 class="pt-1 text-headline-sm font-bold text-on-surface">{{ $ac['nama'] }}</h3>

                            <p class="pt-1.5 pb-4 text-[14px] leading-5 text-text-secondary">{{ implode(', ', $ac['spesifikasi']) }}</p>

                            <p class="mt-auto text-[20px] leading-7 font-bold text-primary tabular-nums">{{ $rupiah($ac['harga']) }}</p>

                            <div class="pt-3">
                                <x-pengguna.tombol-beli :barang="$ac" :nama="$ac['merek'].' '.$ac['nama']" />
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Suku cadang --}}
        <section data-saring-bagian class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-labelledby="judul-suku-cadang">
            <h2 id="judul-suku-cadang" class="mb-4 text-headline-md text-on-surface">Suku cadang dan komponen</h2>

            <div data-saring-daftar class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-5 xl:grid-cols-4">
                @foreach ($produk as $barang)
                    <article
                        data-saring-item
                        data-kategori="{{ $barang['slug_kategori'] }}"
                        data-merek="{{ $merekDisebut($barang['nama'].' '.$barang['deskripsi']) }}"
                        data-harga="{{ $barang['harga'] }}"
                        data-teks="{{ Str::lower($barang['nama'].' '.$barang['kategori'].' '.$barang['deskripsi']) }}"
                        class="flex flex-col overflow-hidden rounded-2xl border border-border-subtle bg-white"
                    >
                        <div class="relative aspect-[16/10] bg-surface-container-low">
                            <x-pengguna.foto :src="$barang['gambar']" :alt="$barang['nama']" ikon="settings" />
                            <x-pengguna.stok :stok="$barang['stok']" class="absolute top-3 left-3" />
                        </div>

                        <div class="flex flex-1 flex-col p-4">
                            <p class="text-label-sm text-text-secondary">{{ $barang['kategori'] }}</p>
                            <h3 class="pt-1 text-headline-sm font-bold text-on-surface">{{ $barang['nama'] }}</h3>
                            <p class="mt-1.5 mb-3 line-clamp-2 text-[14px] leading-5 text-text-secondary">{{ $barang['deskripsi'] }}</p>

                            <p class="mt-auto flex items-baseline gap-1">
                                <span class="text-[20px] leading-7 font-bold text-primary tabular-nums">{{ $rupiah($barang['harga']) }}</span>
                                @if ($barang['satuan'])
                                    <span class="text-label-sm text-text-secondary">/ {{ $barang['satuan'] }}</span>
                                @endif
                            </p>

                            <div class="pt-3">
                                <x-pengguna.tombol-beli :barang="$barang" :nama="$barang['nama']" />
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </section>

        <div class="mx-auto w-full max-w-[1280px] px-4 lg:px-8">
            <div data-saring-kosong hidden class="mb-10 flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-white px-6 py-12 text-center lg:mb-12">
                <span class="material-symbols-outlined text-[40px] text-text-secondary" aria-hidden="true">search_off</span>
                <p class="text-body-semibold text-text-primary">Barang yang dicari belum ada di katalog.</p>
                <p class="max-w-[480px] text-body-md text-text-secondary">Coba kata lain atau hapus saringan. Bisa juga tanyakan lewat WhatsApp, barang tertentu bisa kami pesankan.</p>
                <div class="flex flex-wrap justify-center gap-3 pt-2">
                    <button type="button" data-saring-reset class="flex min-h-11 items-center rounded-full border border-border-subtle px-5 text-label-lg text-primary hover:bg-surface-tint">Hapus saringan</button>
                    <a href="{{ config('usaha.whatsapp.tautan') }}" target="_blank" rel="noopener" class="flex min-h-11 items-center gap-2 rounded-full border border-whatsapp/40 bg-whatsapp/20 px-5 text-label-lg text-text-primary">
                        <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" />
                        Tanya lewat WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Cara menerima pesanan --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-labelledby="judul-cara-terima">
        <div class="rounded-3xl border border-border-subtle bg-white p-5 sm:p-6">
            <h2 id="judul-cara-terima" class="text-headline-md text-on-surface">Pilih cara menerima barang</h2>
            <p class="pt-1 text-body-md text-text-secondary">Cara penerimaan dipilih saat checkout.</p>

            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                @foreach ($caraTerima as $cara)
                    <div class="flex flex-col gap-2 rounded-2xl bg-surface-container-low p-5">
                        <span class="flex size-10 items-center justify-center rounded-xl bg-white text-primary-container">
                            <span class="material-symbols-outlined" aria-hidden="true">{{ $cara['ikon'] }}</span>
                        </span>
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h3 class="text-headline-sm font-bold text-on-surface">{{ $cara['judul'] }}</h3>
                            <p class="text-label-md text-status-success">{{ $cara['biaya'] }}</p>
                        </div>
                        <p class="text-body-md text-text-secondary">{{ $cara['deskripsi'] }}</p>
                        <p class="mt-auto border-t border-border-subtle pt-3 text-label-md text-secondary">{{ $cara['catatan'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
</x-layouts.pengguna>
