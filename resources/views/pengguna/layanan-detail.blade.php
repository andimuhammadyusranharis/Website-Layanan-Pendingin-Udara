<x-layouts.pengguna :title="$layanan['nama']">
    {{-- Variabel: $layanan (satu layanan, bentuk sama dengan daftar plus deskripsi_lengkap), $layananTerkait --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $tautanWhatsapp = fn (string $pesan): string => config('usaha.whatsapp.tautan').'?text='.rawurlencode($pesan);

        $harga = $layanan['perlu_survei'] ? 'Setelah survei' : $rupiah($layanan['harga_mulai']);

        if ($layanan['perlu_survei']) {
            $aksiUtama = ['label' => 'Ajukan survei', 'tautan' => $tautanWhatsapp("Halo, saya ingin mengajukan survei untuk {$layanan['nama']}."), 'luar' => true];
        } elseif (Route::has('pesan.create')) {
            $aksiUtama = ['label' => 'Pesan layanan', 'tautan' => route('pesan.create', ['layanan' => $layanan['id']]), 'luar' => false];
        } else {
            $aksiUtama = ['label' => 'Pesan layanan', 'tautan' => $tautanWhatsapp("Halo, saya ingin memesan {$layanan['nama']}."), 'luar' => true];
        }

        $kelasTombolUtama = 'flex min-h-12 items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-label-lg text-white transition-colors duration-200 hover:bg-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container';
        $kelasTombolKedua = 'flex min-h-12 items-center justify-center gap-2 rounded-full border border-border-subtle bg-white px-6 text-label-lg text-primary transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint';
    @endphp

    <div class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-8 lg:px-8 lg:pt-8 lg:pb-12">
        <nav aria-label="Jejak halaman" class="mb-5">
            <ol class="flex flex-wrap items-center gap-1.5 text-label-md text-text-secondary">
                <li><a href="{{ route('beranda') }}" class="hover:text-primary hover:underline">Beranda</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('layanan.index') }}" class="hover:text-primary hover:underline">Layanan</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-text-primary">{{ $layanan['nama'] }}</li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-x-10 lg:gap-y-6">
            {{-- Foto --}}
            <div class="aspect-[8/5] overflow-hidden rounded-2xl border border-border-subtle bg-surface-container-low lg:col-span-7">
                <x-pengguna.foto :src="$layanan['gambar']" :alt="$layanan['nama']" ikon="ac_unit" />
            </div>

            {{-- Harga dan ajakan memesan --}}
            <aside class="lg:col-span-5 lg:col-start-8 lg:row-span-2 lg:row-start-1" aria-label="Harga dan pemesanan">
                <div class="flex flex-col gap-4 rounded-2xl border border-border-subtle bg-white p-5 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] lg:sticky lg:top-24">
                    <div class="flex flex-col gap-1">
                        @if ($layanan['kategori_label'])
                            <p class="text-label-sm text-primary">{{ $layanan['kategori_label'] }}</p>
                        @endif
                        <h1 class="text-headline-lg-mobile text-on-surface">{{ $layanan['nama'] }}</h1>
                        <p class="text-body-md text-text-secondary">{{ $layanan['deskripsi'] }}</p>
                    </div>

                    <div class="flex flex-col gap-1 border-y border-border-subtle py-4">
                        <span class="text-label-sm text-text-secondary">{{ $layanan['keterangan_harga'] }}</span>
                        <span class="flex items-baseline gap-1">
                            <span class="text-[28px] leading-9 font-bold text-primary tabular-nums">{{ $harga }}</span>
                            @if ($layanan['satuan'] && ! $layanan['perlu_survei'])
                                <span class="text-label-md text-text-secondary">/ {{ $layanan['satuan'] }}</span>
                            @endif
                        </span>
                        @if ($layanan['catatan_harga'])
                            <span class="text-label-md text-on-surface-variant">{{ $layanan['catatan_harga'] }}</span>
                        @endif
                    </div>

                    @if ($layanan['durasi'] || $layanan['jenis_ac'])
                        <dl class="flex flex-col gap-3">
                            @if ($layanan['durasi'])
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[22px] text-primary-container" aria-hidden="true">schedule</span>
                                    <div>
                                        <dt class="text-label-sm text-text-secondary">Lama pengerjaan</dt>
                                        <dd class="text-body-md text-text-primary">{{ $layanan['durasi'] }}</dd>
                                    </div>
                                </div>
                            @endif
                            @if ($layanan['jenis_ac'])
                                <div class="flex items-start gap-3">
                                    <span class="material-symbols-outlined text-[22px] text-primary-container" aria-hidden="true">ac_unit</span>
                                    <div>
                                        <dt class="text-label-sm text-text-secondary">Jenis AC</dt>
                                        <dd class="text-body-md text-text-primary">{{ $layanan['jenis_ac'] }}</dd>
                                    </div>
                                </div>
                            @endif
                        </dl>
                    @endif

                    <div class="flex flex-col gap-3">
                        <a href="{{ $aksiUtama['tautan'] }}" @if ($aksiUtama['luar']) target="_blank" rel="noopener" @endif class="{{ $kelasTombolUtama }} hidden lg:flex">{{ $aksiUtama['label'] }}</a>
                        <a href="{{ $tautanWhatsapp("Halo, saya ingin bertanya tentang {$layanan['nama']}.") }}" target="_blank" rel="noopener" class="{{ $kelasTombolKedua }}">
                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">chat</span>
                            Tanya lewat WhatsApp
                        </a>
                    </div>

                    <p class="text-label-sm text-text-secondary">Biaya akhir disampaikan teknisi setelah melihat kondisi AC. Dibayar setelah pekerjaan selesai.</p>
                </div>
            </aside>

            {{-- Penjelasan --}}
            <div class="flex flex-col gap-6 lg:col-span-7">
                <section aria-labelledby="judul-tentang" class="flex flex-col gap-2">
                    <h2 id="judul-tentang" class="text-headline-md text-on-surface">Tentang layanan ini</h2>
                    <p class="max-w-[68ch] text-body-md whitespace-pre-line text-text-primary">{{ $layanan['deskripsi_lengkap'] }}</p>
                </section>

                @if (count($layanan['fitur']) > 0)
                    <section aria-labelledby="judul-dikerjakan" class="flex flex-col gap-3">
                        <h2 id="judul-dikerjakan" class="text-headline-md text-on-surface">Yang dikerjakan</h2>
                        <ul class="flex flex-col gap-2">
                            @foreach ($layanan['fitur'] as $fitur)
                                <li class="flex items-start gap-2 text-body-md text-text-primary">
                                    <span class="material-symbols-outlined text-[22px] text-status-success" aria-hidden="true">check_circle</span>
                                    {{ $fitur }}
                                </li>
                            @endforeach
                        </ul>
                    </section>
                @endif
            </div>
        </div>

        {{-- Layanan lain --}}
        @if (count($layananTerkait) > 0)
            <section class="mt-10 lg:mt-12" aria-labelledby="judul-terkait">
                <div class="mb-4 flex flex-wrap items-end justify-between gap-2">
                    <h2 id="judul-terkait" class="text-headline-md text-on-surface">Layanan lainnya</h2>
                    <a href="{{ route('layanan.index') }}" class="flex min-h-11 items-center text-label-md text-primary hover:underline">Lihat semua layanan</a>
                </div>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 lg:gap-5">
                    @foreach ($layananTerkait as $item)
                        <a href="{{ route('layanan.show', $item['slug']) }}" class="flex flex-col gap-1 rounded-2xl border border-border-subtle bg-white p-4 transition-colors duration-200 hover:border-primary-container hover:bg-surface-tint">
                            <span class="text-headline-sm font-bold text-on-surface">{{ $item['nama'] }}</span>
                            <span class="line-clamp-2 text-[14px] leading-5 text-text-secondary">{{ $item['deskripsi'] }}</span>
                            <span class="pt-2 text-[17px] leading-6 font-bold text-primary tabular-nums">{{ $item['perlu_survei'] ? 'Setelah survei' : $rupiah($item['harga_mulai']) }}</span>
                        </a>
                    @endforeach
                </div>
            </section>
        @endif
    </div>

    {{-- Bar pemesanan menempel di bawah layar ponsel dan berhenti sebelum footer --}}
    <div class="sticky bottom-0 z-30 border-t border-border-subtle bg-white px-4 py-3 shadow-[0px_-4px_20px_rgba(20,64,127,0.08)] lg:hidden">
        <div class="mx-auto flex max-w-[1280px] items-center gap-3">
            <p class="flex min-w-0 flex-1 flex-col">
                <span class="truncate text-label-sm text-text-secondary">{{ $layanan['keterangan_harga'] }}</span>
                <span class="text-[18px] leading-6 font-bold text-primary tabular-nums">{{ $harga }}</span>
            </p>
            <a href="{{ $aksiUtama['tautan'] }}" @if ($aksiUtama['luar']) target="_blank" rel="noopener" @endif class="{{ $kelasTombolUtama }} shrink-0">{{ $aksiUtama['label'] }}</a>
        </div>
    </div>
</x-layouts.pengguna>
