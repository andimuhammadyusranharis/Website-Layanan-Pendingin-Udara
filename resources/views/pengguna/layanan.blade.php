<x-layouts.pengguna title="Layanan">
    {{-- Variabel: $kategoriLayanan, $layanan, $simulasiEstimasi --}}
    @php
        $jaminan = [
        ];

        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $tautanWhatsapp = fn (string $pesan): string => config('usaha.whatsapp.tautan').'?text='.rawurlencode($pesan);

        $tautanPesan = fn (array $item): string => Route::has('pesan.create')
            ? route('pesan.create', ['layanan' => $item['id']])
            : $tautanWhatsapp("Halo, saya ingin memesan {$item['nama']}.");

        $tautanEstimasi = Route::has('estimasi') ? route('estimasi') : null;

        $kelasPilihan = 'flex min-h-11 items-center rounded-full border border-border-subtle bg-white px-4 text-label-md lg:min-h-10 lg:px-3.5 text-on-surface-variant transition-colors duration-200 hover:border-primary-container hover:text-primary aria-pressed:border-primary-container aria-pressed:bg-primary-container aria-pressed:text-white focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container';
    @endphp

    <div data-saring>
        {{-- Judul dan pencarian --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pt-8 pb-8 lg:px-8">
            <div class="flex max-w-[720px] flex-col gap-3">
                <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Daftar layanan</h1>
                <p class="text-body-md text-text-secondary lg:text-body-lg">
                    Cuci, isi freon, perbaikan, dan pemasangan AC untuk rumah, ruko, dan kantor di Kabupaten Pangkajene dan Kepulauan. Harga di bawah adalah tarif awal. Biaya akhir disampaikan teknisi setelah melihat kondisi AC.
                </p>
            </div>

            @if (count($jaminan) > 0)
                <ul class="mt-4 flex flex-wrap gap-x-6 gap-y-2">
                    @foreach ($jaminan as $poin)
                        <li class="flex items-center gap-2 text-body-semibold text-text-primary">
                            <span class="material-symbols-outlined text-[20px] text-primary-container" aria-hidden="true">{{ $poin['ikon'] }}</span>
                            {{ $poin['teks'] }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <div class="mt-6 flex flex-col gap-3 rounded-2xl border border-border-subtle bg-white p-4 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] sm:p-5">
                <label class="relative flex items-center">
                    <span class="sr-only">Cari layanan</span>
                    <span class="material-symbols-outlined pointer-events-none absolute left-4 text-text-secondary" aria-hidden="true">search</span>
                    <input
                        type="search"
                        data-saring-cari
                        placeholder="Contoh: cuci split, freon R32, kapasitor"
                        class="h-12 w-full rounded-xl border border-border-subtle bg-surface-container-low pr-4 pl-12 text-body-md text-text-primary placeholder:text-text-secondary focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none"
                    />
                </label>

                <div class="flex flex-wrap gap-2" role="group" aria-label="Saring menurut jenis layanan">
                    <button type="button" data-saring-pilihan="kategori" data-nilai="" aria-pressed="true" class="{{ $kelasPilihan }}">Semua ({{ count($layanan) }})</button>
                    @foreach ($kategoriLayanan as $kategori)
                        <button type="button" data-saring-pilihan="kategori" data-nilai="{{ $kategori['slug'] }}" aria-pressed="false" class="{{ $kelasPilihan }}">{{ $kategori['nama'] }}</button>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- Daftar layanan --}}
        <section class="mx-auto w-full max-w-[1280px] px-4 pb-10 lg:px-8 lg:pb-12" aria-labelledby="judul-paket">
            <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                <h2 id="judul-paket" class="text-headline-md text-on-surface">Paket pengerjaan</h2>
                <p data-saring-status data-satuan="layanan" role="status" class="text-body-md text-text-secondary">{{ count($layanan) }} layanan ditampilkan</p>
            </div>

            @if (count($layanan) > 0)
                <div data-saring-daftar class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 lg:gap-5">
                    @foreach ($layanan as $item)
                        <article
                            data-saring-item
                            data-kategori="{{ $item['kategori'] }}"
                            data-harga="{{ $item['harga_mulai'] ?? 0 }}"
                            data-teks="{{ Str::lower($item['nama'].' '.$item['jenis_ac'].' '.$item['deskripsi'].' '.implode(' ', $item['fitur'])) }}"
                            class="flex flex-col overflow-hidden rounded-2xl border border-border-subtle bg-white"
                        >
                            <div class="relative aspect-[5/2] bg-surface-container-low">
                                <x-pengguna.foto :src="$item['gambar']" :alt="$item['nama']" ikon="ac_unit" />
                                @if ($item['durasi'])
                                    <span class="absolute right-3 bottom-3 flex items-center gap-1 rounded-full bg-white px-3 py-1 text-label-sm text-text-primary">
                                        <span class="material-symbols-outlined text-[16px]" aria-hidden="true">schedule</span>
                                        {{ $item['durasi'] }}
                                    </span>
                                @endif
                            </div>

                            <div class="flex flex-1 flex-col p-5">
                                <p class="text-label-sm text-primary">{{ $item['jenis_ac'] }}</p>

                                <h3 class="pt-1.5 text-headline-sm font-bold text-on-surface">{{ $item['nama'] }}</h3>
                                <p class="mt-1 line-clamp-2 text-[14px] leading-5 text-text-secondary">{{ $item['deskripsi'] }}</p>

                                <ul class="flex flex-col gap-1 pt-3 pb-4">
                                    @foreach ($item['fitur'] as $fitur)
                                        <li class="flex items-start gap-2 text-[14px] leading-5 text-text-primary">
                                            <span class="material-symbols-outlined text-[18px] text-status-success" aria-hidden="true">check_circle</span>
                                            {{ $fitur }}
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="mt-auto flex items-end justify-between gap-3 border-t border-border-subtle pt-3">
                                    <p class="flex flex-col">
                                        <span class="text-label-sm text-text-secondary">{{ $item['keterangan_harga'] }}</span>
                                        <span class="flex items-baseline gap-1">
                                            <span class="text-[20px] leading-7 font-bold text-primary tabular-nums">{{ $item['perlu_survei'] ? 'Setelah survei' : $rupiah($item['harga_mulai']) }}</span>
                                            @if ($item['satuan'])
                                                <span class="text-label-sm text-text-secondary">/ {{ $item['satuan'] }}</span>
                                            @endif
                                        </span>
                                    </p>
                                    @if ($item['catatan_harga'])
                                        <span class="rounded-lg bg-surface-container-low px-2.5 py-1 text-right text-label-sm text-on-surface-variant">{{ $item['catatan_harga'] }}</span>
                                    @endif
                                </div>

                                <div class="flex gap-3 pt-4">
                                    @if (Route::has('layanan.show'))
                                        <a href="{{ route('layanan.show', $item['id']) }}" class="flex min-h-11 flex-1 items-center justify-center rounded-full border border-border-subtle px-4 text-label-lg text-primary transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint">Lihat detail</a>
                                    @endif

                                    @if ($item['perlu_survei'])
                                        <a
                                            href="{{ $tautanWhatsapp("Halo, saya ingin mengajukan survei untuk {$item['nama']}.") }}"
                                            target="_blank"
                                            rel="noopener"
                                            class="flex min-h-11 flex-1 items-center justify-center gap-2 rounded-full bg-primary-container px-4 text-label-lg text-white transition-colors duration-200 hover:bg-primary"
                                        >
                                            Ajukan survei
                                        </a>
                                    @else
                                        <a
                                            href="{{ $tautanPesan($item) }}"
                                            @unless (Route::has('pesan.create')) target="_blank" rel="noopener" @endunless
                                            class="flex min-h-11 flex-1 items-center justify-center rounded-full bg-primary-container px-4 text-label-lg text-white transition-colors duration-200 hover:bg-primary"
                                        >
                                            Pesan layanan
                                        </a>
                                    @endif
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
