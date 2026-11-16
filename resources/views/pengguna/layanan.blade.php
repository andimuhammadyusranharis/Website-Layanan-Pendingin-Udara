<x-layouts.pengguna title="Layanan">
    {{-- Variabel: $kategoriLayanan, $layanan, $simulasiEstimasi --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $tautanWhatsapp = fn (string $pesan): string => config('usaha.whatsapp.tautan').'?text='.rawurlencode($pesan);

        $tautanEstimasi = Route::has('estimasi') ? route('estimasi') : null;

        $kelasLihatDetail = 'inline-flex h-9 shrink-0 items-center gap-1 rounded-full border border-border-subtle bg-white pr-2.5 pl-3.5 text-label-md text-primary transition-colors duration-200 group-focus-within:border-primary-container group-focus-within:bg-primary-container group-focus-within:text-white group-hover:border-primary-container group-hover:bg-primary-container group-hover:text-white motion-reduce:transition-none';

        $kelasPilihan ='flex min-h-11 shrink-0 items-center rounded-full border border-border-subtle bg-white px-4 text-label-md text-on-surface-variant transition-colors duration-200 hover:border-primary-container hover:text-primary aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container lg:min-h-10 lg:px-3.5';
    @endphp

    <div data-saring>
        {{-- Judul, pencarian, dan penyaring dalam satu blok ringkas --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-6 lg:px-8">
            <div class="flex flex-wrap items-end justify-between gap-x-6 gap-y-1">
                <div class="flex max-w-[720px] flex-col gap-1">
                    <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Daftar layanan</h1>
                    <p class="text-body-md text-text-secondary">Servis AC untuk rumah, ruko, dan kantor. Harga di bawah adalah tarif awal.</p>
                </div>
                <p data-saring-status data-satuan="layanan" role="status" class="text-body-md text-text-secondary">{{ count($layanan) }} layanan ditampilkan</p>
            </div>

            @if (count($layanan) > 0)
                <div class="mt-5 flex flex-col gap-3 lg:flex-row lg:items-center">
                    <label class="relative flex items-center md:max-w-[420px] lg:w-[320px] lg:shrink-0">
                        <span class="sr-only">Cari layanan</span>
                        <span class="material-symbols-outlined pointer-events-none absolute left-3.5 text-[22px] text-text-secondary" aria-hidden="true">search</span>
                        <input
                            type="search"
                            data-saring-cari
                            placeholder="Cari layanan, misalnya freon"
                            class="h-11 w-full rounded-full border border-border-subtle bg-white pr-4 pl-11 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
                        />
                    </label>

                    @if (count($kategoriLayanan) > 1)
                        <div class="-mx-4 flex snap-x scroll-px-4 gap-2 overflow-x-auto px-4 pb-1 [scrollbar-width:none] md:mx-0 md:flex-wrap md:overflow-visible md:px-0 md:pb-0 [&::-webkit-scrollbar]:hidden" role="group" aria-label="Saring menurut jenis layanan">
                            <button type="button" data-saring-pilihan="kategori" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua</button>
                            @foreach ($kategoriLayanan as $kategori)
                                <button type="button" data-saring-pilihan="kategori" data-nilai="{{ $kategori['slug'] }}" aria-pressed="false" class="{{ $kelasPilihan }}">{{ $kategori['nama'] }}</button>
                            @endforeach
                        </div>
                    @endif
                </div>
            @endif
        </section>

        {{-- Daftar layanan --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-label="Daftar layanan">
            @if (count($layanan) > 0)
                <div data-saring-daftar class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-5">
                    @foreach ($layanan as $item)
                        <article
                            data-saring-item
                            data-kategori="{{ $item['kategori'] }}"
                            data-harga="{{ $item['harga_mulai'] ?? 0 }}"
                            data-teks="{{ Str::lower($item['nama'].' '.$item['jenis_ac'].' '.$item['deskripsi'].' '.implode(' ', $item['fitur'])) }}"
                            class="group relative flex min-w-0 flex-col overflow-hidden rounded-2xl border border-border-subtle bg-white transition-[border-color,box-shadow] duration-200 focus-within:border-primary-container focus-within:ring-4 focus-within:ring-primary-container/15 hover:border-primary-container/50 hover:shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.12)] motion-reduce:transition-none"
                        >
                            <div class="aspect-[8/5] overflow-hidden bg-surface-container-low">
                                <x-pengguna.foto :src="$item['gambar']" :alt="$item['nama']" ikon="ac_unit" class="transition-transform duration-300 group-hover:scale-[1.03] motion-reduce:transition-none motion-reduce:group-hover:scale-100" />
                            </div>

                            <div class="flex flex-1 flex-col gap-1 p-4">
                                @if ($item['kategori_label'])
                                    <p class="text-label-sm text-primary">{{ $item['kategori_label'] }}</p>
                                @endif

                                <h2 class="text-headline-sm font-bold break-words text-on-surface">
                                    <a href="{{ route('layanan.show', $item['slug']) }}" class="after:absolute after:inset-0 focus-visible:outline-none">{{ $item['nama'] }}</a>
                                </h2>
                                <p class="line-clamp-2 text-[14px] leading-5 text-text-secondary">{{ $item['deskripsi'] }}</p>

                                <div class="mt-auto flex flex-wrap items-end justify-between gap-x-3 gap-y-2 pt-3">
                                    <p class="flex flex-col">
                                        <span class="text-label-sm text-text-secondary">{{ $item['keterangan_harga'] }}</span>
                                        <span class="flex items-baseline gap-1">
                                            <span class="text-[20px] leading-7 font-bold text-primary tabular-nums">{{ $item['perlu_survei'] ? 'Setelah survei' : $rupiah($item['harga_mulai']) }}</span>
                                            @if ($item['satuan'] && ! $item['perlu_survei'])
                                                <span class="text-label-sm text-text-secondary">/ {{ $item['satuan'] }}</span>
                                            @endif
                                        </span>
                                    </p>
                                    <span class="{{ $kelasLihatDetail }}" aria-hidden="true">
                                        Lihat detail
                                        <span class="material-symbols-outlined text-[18px] transition-transform duration-200 group-focus-within:translate-x-0.5 group-hover:translate-x-0.5 motion-reduce:transition-none motion-reduce:group-hover:translate-x-0">arrow_forward</span>
                                    </span>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div data-saring-kosong hidden class="flex flex-col items-center gap-3 rounded-2xl border border-dashed border-outline-variant bg-white px-6 py-12 text-center">
                    <span class="material-symbols-outlined text-[40px] text-text-secondary" aria-hidden="true">search_off</span>
                    <p class="text-body-semibold text-text-primary">Tidak ada layanan yang cocok.</p>
                    <p class="max-w-[480px] text-body-md text-text-secondary">Coba kata lain, misalnya "cuci" atau "freon", atau tanyakan keluhan AC Anda lewat WhatsApp.</p>
                    <div class="flex flex-wrap justify-center gap-3 pt-2">
                        <button type="button" data-saring-reset class="flex min-h-11 items-center rounded-full border border-border-subtle px-5 text-label-lg text-primary hover:bg-surface-tint">Tampilkan semua layanan</button>
                        <a href="{{ config('usaha.whatsapp.tautan') }}" target="_blank" rel="noopener" class="flex min-h-11 items-center gap-2 rounded-full border border-whatsapp/40 bg-whatsapp/20 px-5 text-label-lg text-text-primary">
                            <img src="{{ asset('images/komponen-ikon-whatsapp.svg') }}" alt="" />
                            Tanya lewat WhatsApp
                        </a>
                    </div>
                </div>
            @else
                <p class="rounded-2xl border border-border-subtle bg-white p-6 text-body-md text-text-secondary">Daftar layanan belum tersedia. Hubungi kami lewat WhatsApp untuk menanyakan jadwal dan tarif.</p>
            @endif
        </section>
    </div>

    {{-- Simulasi estimasi --}}
    <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-labelledby="judul-estimasi">
        <div class="grid grid-cols-1 items-center gap-6 rounded-3xl bg-primary-container p-6 sm:p-8 lg:grid-cols-12 lg:gap-10">
            <div class="flex flex-col gap-3 lg:col-span-7">
                <h2 id="judul-estimasi" class="text-headline-lg-mobile text-white">Punya beberapa unit AC di rumah atau kantor?</h2>
                <p class="text-body-md text-on-primary-container">Hitung perkiraan biaya untuk semua unit sekaligus sebelum memesan. Tarif final dikonfirmasi teknisi setelah melihat kondisi AC.</p>
                <div class="flex flex-col gap-3 pt-2 sm:flex-row">
                    @if ($tautanEstimasi)
                        <a href="{{ $tautanEstimasi }}" class="flex min-h-12 items-center justify-center gap-2 rounded-full bg-white px-7 text-label-lg text-primary transition-colors duration-200 hover:bg-surface-tint">
                            <span class="material-symbols-outlined" aria-hidden="true">calculate</span>
                            Hitung estimasi biaya
                        </a>
                    @endif
                    <a
                        href="{{ $tautanWhatsapp('Halo, saya ingin menanyakan estimasi biaya servis AC.') }}"
                        target="_blank"
                        rel="noopener"
                        @class([
                            'flex min-h-12 items-center justify-center gap-2 rounded-full px-7 text-label-lg transition-colors duration-200',
                            'bg-white/15 text-white hover:bg-white/25' => $tautanEstimasi,
                            'bg-white text-primary hover:bg-surface-tint' => ! $tautanEstimasi,
                        ])
                    >
                        <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                        Tanya estimasi lewat WhatsApp
                    </a>
                </div>
            </div>

            <div class="rounded-2xl bg-white p-5 lg:col-span-5">
                <p class="text-label-lg text-secondary">Contoh: {{ Str::lcfirst($simulasiEstimasi['judul']) }}</p>
                <dl class="mt-3 flex flex-col gap-2 border-b border-border-subtle pb-3">
                    @foreach ($simulasiEstimasi['rincian'] as $baris)
                        <div class="flex items-center justify-between gap-4 text-body-md">
                            <dt class="text-text-secondary">{{ $baris['nama'] }}</dt>
                            <dd class="font-semibold tabular-nums text-text-primary">{{ $baris['biaya'] > 0 ? $rupiah($baris['biaya']) : 'Gratis' }}</dd>
                        </div>
                    @endforeach
                </dl>
                <div class="flex items-baseline justify-between gap-4 pt-3">
                    <p class="text-body-semibold text-text-primary">Estimasi total</p>
                    <p class="text-headline-md tabular-nums text-secondary">{{ $rupiah($simulasiEstimasi['total']) }}</p>
                </div>
                <p class="pt-2 text-label-sm text-text-secondary">Dibayar setelah pekerjaan selesai, tunai atau transfer.</p>
            </div>
        </div>
    </section>
</x-layouts.pengguna>
