<x-layouts.pengguna :title="trim($produk['merek'].' '.$produk['nama'])">
    {{-- Variabel: $produk (satu produk, bentuknya sama dengan kartu di halaman Toko) --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $namaLengkap = trim($produk['merek'].' '.$produk['nama']);
        $unitAc = $produk['slug_kategori'] === \App\Enums\KategoriProduk::UnitAc->value;
        $tautanTanya = config('usaha.whatsapp.tautan').'?text='.rawurlencode("Halo, saya ingin bertanya tentang {$namaLengkap}.");

        $kelasTombolKedua = 'flex min-h-12 items-center justify-center gap-2 rounded-full border border-border-subtle bg-white px-6 text-label-lg text-primary transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint';
    @endphp

    <div class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-8 lg:px-8 lg:pt-8 lg:pb-12">
        <nav aria-label="Jejak halaman" class="mb-5">
            <ol class="flex flex-wrap items-center gap-1.5 text-label-md text-text-secondary">
                <li><a href="{{ route('beranda') }}" class="hover:text-primary hover:underline">Beranda</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('toko.index') }}" class="hover:text-primary hover:underline">Toko</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-text-primary">{{ $namaLengkap }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-x-10 lg:gap-y-6">
            {{-- Foto --}}
            <div class="aspect-[8/5] overflow-hidden rounded-2xl border border-border-subtle bg-surface-container-low lg:col-span-7">
                <x-pengguna.foto :src="$produk['gambar']" :alt="$namaLengkap" :ikon="$unitAc ? 'ac_unit' : 'settings'" />
            </div>

            {{-- Harga dan pembelian --}}
            <aside class="lg:col-span-5 lg:col-start-8 lg:row-span-2 lg:row-start-1" aria-label="Harga dan pembelian">
                <div class="flex flex-col gap-4 rounded-2xl border border-border-subtle bg-white p-5 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] lg:sticky lg:top-24">
                    <div class="flex flex-col gap-1">
                        <p class="text-label-sm text-primary">{{ $produk['kategori'] }}</p>
                        @if ($produk['merek'] !== '')
                            <p class="text-label-md text-text-secondary">{{ $produk['merek'] }} {{ $produk['model'] }}</p>
                        @endif
                        <h1 class="text-headline-lg-mobile text-on-surface">{{ $produk['nama'] }}</h1>
                    </div>

                    <div class="flex flex-col gap-2 border-y border-border-subtle py-4">
                        <span class="flex items-baseline gap-1">
                            <span class="text-[28px] leading-9 font-bold text-primary tabular-nums">{{ $rupiah($produk['harga']) }}</span>
                            @if ($produk['satuan'])
                                <span class="text-label-md text-text-secondary">/ {{ $produk['satuan'] }}</span>
                            @endif
                        </span>
                        <div>
                            <x-pengguna.stok :stok="$produk['stok']" :satuan="$unitAc ? 'unit' : null" />
                        </div>
                    </div>

                    <div class="flex flex-col gap-3">
                        <x-pengguna.tombol-beli :barang="$produk" :nama="$namaLengkap" />
                        <a href="{{ $tautanTanya }}" target="_blank" rel="noopener" class="{{ $kelasTombolKedua }}">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chat</span>
                            Tanya lewat WhatsApp
                        </a>
                    </div>
                </div>
            </aside>

            {{-- Penjelasan --}}
            <div class="flex flex-col gap-6 lg:col-span-7">
                <section aria-labelledby="judul-tentang" class="flex flex-col gap-2">
                    <h2 id="judul-tentang" class="text-headline-md text-on-surface">Tentang barang ini</h2>
                    <p class="max-w-[68ch] text-body-md whitespace-pre-line text-text-primary">{{ $produk['deskripsi'] }}</p>
                </section>

                @if (count($produk['spesifikasi']) > 0)
                    <section aria-labelledby="judul-spesifikasi" class="flex flex-col gap-3">
                        <h2 id="judul-spesifikasi" class="text-headline-md text-on-surface">Spesifikasi</h2>
                        <ul class="flex flex-col gap-2">
                            @foreach ($produk['spesifikasi'] as $poin)
                                <li class="flex items-start gap-2 text-body-md text-text-primary">
                                    <span class="material-symbols-outlined text-[22px] text-status-success" aria-hidden="true">check_circle</span>
                                    {{ $poin }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>
    </div>
</x-layouts.pengguna>

