<x-layouts.pengguna title="Estimasi biaya">
    {{-- Variabel: $layanan, $jenisAc, $estimasi, $galat, $terpilihLayanan, $terpilihJenis, $jumlah --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $kelasIsian = 'mt-2 h-12 w-full rounded-xl border border-transparent bg-surface-container-low px-4 text-[16px] leading-6 text-text-primary transition-colors duration-200 focus:border-primary-container focus:bg-white focus:ring-4 focus:ring-primary-container/15 focus:outline-none';
    @endphp

    <div class="mx-auto w-full max-w-[1280px] px-4 pt-6 pb-8 lg:px-8 lg:pt-8 lg:pb-12">
        <nav aria-label="Jejak halaman" class="mb-5">
            <ol class="flex flex-wrap items-center gap-1.5 text-label-md text-text-secondary">
                <li><a href="{{ route('beranda') }}" class="hover:text-primary hover:underline">Beranda</a></li>
                <li aria-hidden="true">/</li>
                <li><a href="{{ route('layanan.index') }}" class="hover:text-primary hover:underline">Layanan</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page" class="text-text-primary">Estimasi biaya</li>
            </ol>
        </nav>

        <h1 class="text-headline-lg-mobile text-on-surface lg:text-headline-lg">Estimasi biaya servis AC</h1>
        <p class="mt-1 max-w-[68ch] text-body-md text-text-secondary">Pilih layanan dan jenis AC, lalu masukkan jumlah unit untuk melihat perkiraan biaya sebelum memesan.</p>

        <div class="mt-6 grid grid-cols-1 gap-6 lg:grid-cols-12 lg:gap-10">
            <div class="lg:col-span-7">
                <form action="{{ route('estimasi') }}" method="GET" class="flex flex-col gap-5">
                    <div>
                        <label for="pilih-layanan" class="text-[15px] leading-6 font-semibold text-text-primary">
                            Layanan
                            <span class="text-status-danger" aria-hidden="true">*</span>
                        </label>
                        <select id="pilih-layanan" name="layanan" required class="{{ $kelasIsian }}">
                            <option value="">Pilih layanan...</option>
                            @foreach ($layanan as $item)
                                <option value="{{ $item['id'] }}" @selected($terpilihLayanan === $item['id'])>{{ $item['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="pilih-jenis-ac" class="text-[15px] leading-6 font-semibold text-text-primary">
                            Jenis AC
                            <span class="text-status-danger" aria-hidden="true">*</span>
                        </label>
                        <select id="pilih-jenis-ac" name="jenis_ac" required class="{{ $kelasIsian }}">
                            <option value="">Pilih jenis AC...</option>
                            @foreach ($jenisAc as $item)
                                <option value="{{ $item['id'] }}" @selected($terpilihJenis === $item['id'])>{{ $item['nama'] }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="jumlah-unit" class="text-[15px] leading-6 font-semibold text-text-primary">
                            Jumlah unit
                            <span class="text-status-danger" aria-hidden="true">*</span>
                        </label>
                        <input
                            id="jumlah-unit"
                            name="jumlah"
                            type="number"
                            min="1"
                            max="1000"
                            inputmode="numeric"
                            value="{{ max(1, $jumlah) }}"
                            required
                            class="{{ $kelasIsian }}"
                        />
                        <p class="mt-2 text-label-sm text-text-secondary">Contoh: 3 berarti tiga unit AC dalam satu pemesanan.</p>
                    </div>

                    <button
                        type="submit"
                        class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-primary-container px-7 text-label-lg text-white transition-colors duration-200 hover:bg-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container"
                    >
                        <span class="material-symbols-outlined" aria-hidden="true">calculate</span>
                        Hitung estimasi
                    </button>
                </form>

                @if ($galat)
                    <div class="mt-5 flex items-start gap-2 rounded-xl border border-status-danger/40 bg-lencana-merah-latar px-4 py-3 text-[15px] leading-6 text-status-danger">
                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">error</span>
                        {{ $galat }}
                    </div>
                @endif
            </div>

            <div class="lg:col-span-5">
                @if ($estimasi)
                    <div class="flex flex-col gap-4 rounded-2xl border border-border-subtle bg-white p-5 shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06)] lg:sticky lg:top-24">
                        <p class="text-label-lg text-secondary">Estimasi biaya</p>

                        <dl class="flex flex-col gap-2 border-b border-border-subtle pb-3">
                            <div class="flex items-center justify-between gap-4 text-body-md">
                                <dt class="text-text-secondary">Layanan</dt>
                                <dd class="text-right font-semibold text-text-primary">{{ $estimasi['layanan'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-body-md">
                                <dt class="text-text-secondary">Jenis AC</dt>
                                <dd class="text-right font-semibold text-text-primary">{{ $estimasi['jenis_ac'] }}</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-body-md">
                                <dt class="text-text-secondary">Jumlah unit</dt>
                                <dd class="text-right font-semibold text-text-primary">{{ $estimasi['jumlah'] }} unit</dd>
                            </div>
                            <div class="flex items-center justify-between gap-4 text-body-md">
                                <dt class="text-text-secondary">Tarif per unit</dt>
                                <dd class="text-right font-semibold tabular-nums text-text-primary">{{ $rupiah($estimasi['tarif']) }}</dd>
                            </div>
                        </dl>

                        <div class="flex items-baseline justify-between gap-4 pt-1">
                            <p class="text-body-semibold text-text-primary">Total estimasi</p>
                            <p class="text-headline-md tabular-nums text-secondary">{{ $rupiah($estimasi['total']) }}</p>
                        </div>

                        <a
                            href="{{ config('usaha.whatsapp.tautan') }}?text={{ rawurlencode('Halo, saya ingin memesan '.$estimasi['layanan'].' untuk '.$estimasi['jumlah'].' unit '.$estimasi['jenis_ac'].'.') }}"
                            target="_blank"
                            rel="noopener"
                            class="inline-flex min-h-12 items-center justify-center gap-2 rounded-full bg-primary-container px-6 text-label-lg text-white transition-colors duration-200 hover:bg-primary focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-container"
                        >
                            <span class="material-symbols-outlined" aria-hidden="true">chat</span>
                            Pesan lewat WhatsApp
                        </a>

                        <p class="text-label-sm text-text-secondary">Tarif final dikonfirmasi teknisi setelah melihat kondisi AC. Dibayar setelah pekerjaan selesai.</p>
                    </div>
                @else
                    <div class="flex flex-col items-center gap-2 rounded-2xl border border-dashed border-border-subtle bg-white px-6 py-12 text-center">
                        <span class="material-symbols-outlined text-[40px] text-text-secondary" aria-hidden="true">calculate</span>
                        <p class="text-body-md text-text-secondary">Isi formulir di samping untuk melihat perkiraan biaya.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.pengguna>
