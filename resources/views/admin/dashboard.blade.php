<x-layouts.admin title="Dashboard">
    {{-- Variabel: $ringkasan, $jadwalHariIni, $verifikasiPembayaran, $pesananBaru, $stokKritis, $pendaftarTeknisi --}}
    @php
        $tautan = fn (string $nama, mixed $parameter = []): ?string => Route::has($nama) ? route($nama, $parameter) : null;

        $rupiah = fn (int $nilai): string => 'Rp'.number_format($nilai, 0, ',', '.');

        $statusJadwal = [
            'menunggu' => ['label' => 'Menunggu', 'kelas' => 'bg-lencana-netral-latar text-lencana-netral'],
            'dijadwalkan' => ['label' => 'Dijadwalkan', 'kelas' => 'bg-lencana-biru-latar text-lencana-biru'],
            'dikerjakan' => ['label' => 'Dikerjakan', 'kelas' => 'bg-lencana-kuning-latar text-lencana-kuning'],
            'selesai' => ['label' => 'Selesai', 'kelas' => 'bg-lencana-hijau-latar text-lencana-hijau'],
        ];

        $statusPesanan = [
            'menunggu_teknisi' => ['label' => 'Menunggu teknisi', 'kelas' => 'text-lencana-kuning', 'aksi' => 'Tugaskan', 'varian' => 'utama'],
            'siap_kirim' => ['label' => 'Siap dikirim driver', 'kelas' => 'text-lencana-hijau', 'aksi' => 'Proses kirim', 'varian' => 'gelap'],
        ];

        $kartuRingkasan = [
            ['label' => 'Pesanan menunggu', 'nilai' => $ringkasan['pesanan_menunggu'], 'route' => 'admin.pesanan.index', 'bahaya' => false],
            ['label' => 'Jadwal kunjungan', 'nilai' => $ringkasan['jadwal_hari_ini'], 'route' => 'admin.jadwal.index', 'bahaya' => false],
            ['label' => 'Verifikasi bayar', 'nilai' => $ringkasan['verifikasi_bayar'], 'route' => 'admin.pembayaran.index', 'bahaya' => false],
            ['label' => 'Stok kritis', 'nilai' => $ringkasan['stok_kritis'], 'route' => 'admin.produk.index', 'bahaya' => $ringkasan['stok_kritis'] > 0],
        ];

        $kelasKartu = 'rounded-2xl border border-border-subtle bg-white shadow-[0px_10px_30px_-5px_rgba(20,64,127,0.06),0px_4px_6px_-2px_rgba(20,64,127,0.03)]';
        $kelasTautanSemua = 'flex min-h-10 items-center gap-1 text-[13px] leading-[18px] font-bold text-primary-container hover:underline';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        {{-- Judul halaman --}}
        <section class="{{ $kelasKartu }} flex flex-col gap-4 p-5 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex flex-col gap-1">
                <h1 class="text-[20px] leading-7 font-bold text-on-surface lg:text-[24px] lg:leading-8">Dashboard operasional</h1>
                <p class="text-[14px] leading-5 text-text-secondary">{{ now()->locale('id')->translatedFormat('l, j F Y') }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    @if ($url = $tautan('teknisi.tugas.index')) href="{{ $url }}" @else aria-disabled="true" title="Halaman teknisi belum tersedia" @endif
                    class="flex min-h-11 items-center gap-2 rounded-full bg-surface-container-low px-5 text-[14px] leading-5 font-semibold text-text-primary transition-colors duration-200 hover:bg-surface-tint"
                >
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">engineering</span>
                    Buka mode lapangan
                </a>
                <a
                    @if ($url = $tautan('admin.pesanan.create')) href="{{ $url }}" @else aria-disabled="true" title="Halaman belum tersedia" @endif
                    class="flex min-h-11 items-center gap-2 rounded-full bg-primary-container px-5 text-[14px] leading-5 font-semibold text-white transition-colors duration-200 hover:bg-primary hover:shadow-[0px_8px_20px_rgba(30,99,214,0.25)]"
                >
                    <span class="material-symbols-outlined text-[20px]" aria-hidden="true">add_circle</span>
                    Pesanan manual
                </a>
            </div>
        </section>

        {{-- Ringkasan angka --}}
        <section aria-labelledby="judul-ringkasan">
            <h2 id="judul-ringkasan" class="sr-only">Ringkasan hari ini</h2>
            <ul class="grid grid-cols-2 gap-4 lg:grid-cols-4 lg:gap-5">
                @foreach ($kartuRingkasan as $kartu)
                    <li>
                        <a
                            @if ($url = $tautan($kartu['route'])) href="{{ $url }}" @endif
                            class="{{ $kelasKartu }} flex h-full flex-col gap-3 p-5 transition-colors duration-200 hover:border-primary-container"
                        >
                            <span class="text-[14px] leading-5 text-text-secondary">{{ $kartu['label'] }}</span>
                            <span @class(['text-[32px] leading-9 font-bold tabular-nums', 'text-status-danger' => $kartu['bahaya'], 'text-on-surface' => ! $kartu['bahaya']])>{{ $kartu['nilai'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        <div class="grid grid-cols-1 items-start gap-4 lg:gap-5 xl:grid-cols-[minmax(0,1.45fr)_minmax(0,1fr)]">
            <div class="flex flex-col gap-4 lg:gap-5">
                {{-- Jadwal kunjungan hari ini --}}
                <section class="{{ $kelasKartu }} p-5" aria-labelledby="judul-jadwal">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle pb-3">
                        <h2 id="judul-jadwal" class="flex items-center gap-2.5 text-[17px] leading-6 font-semibold text-on-surface">
                            <span class="size-2.5 rounded-full bg-primary-container" aria-hidden="true"></span>
                            Jadwal kunjungan hari ini
                        </h2>
                        @if ($url = $tautan('admin.jadwal.index'))
                            <a href="{{ $url }}" class="{{ $kelasTautanSemua }}">Semua <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span></a>
                        @endif
                    </div>

                    @if (count($jadwalHariIni) === 0)
                        <p class="py-8 text-center text-[14px] leading-5 text-text-secondary">Tidak ada kunjungan hari ini.</p>
                    @else
                        <div class="mt-4 overflow-x-auto rounded-xl border border-border-subtle">
                            <table class="w-full min-w-[600px] text-left text-[14px] leading-5">
                                <thead class="bg-surface-container-low text-[13px] leading-[18px] font-bold text-text-secondary">
                                    <tr>
                                        <th scope="col" class="px-4 py-3">Waktu</th>
                                        <th scope="col" class="px-4 py-3">Pelanggan &amp; layanan</th>
                                        <th scope="col" class="px-4 py-3">Teknisi</th>
                                        <th scope="col" class="px-4 py-3">Status</th>
                                        <th scope="col" class="px-4 py-3 text-right"><span class="sr-only">Aksi</span></th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-border-subtle">
                                    @foreach ($jadwalHariIni as $jadwal)
                                        @php($status = $statusJadwal[$jadwal['status']] ?? $statusJadwal['menunggu'])
                                        <tr>
                                            <td class="px-4 py-3 font-bold whitespace-nowrap text-on-surface tabular-nums">{{ $jadwal['waktu'] }}</td>
                                            <td class="px-4 py-3">
                                                <span class="block font-semibold text-on-surface">{{ $jadwal['pelanggan'] }}</span>
                                                <span class="block text-text-secondary">{{ $jadwal['layanan'] }}</span>
                                            </td>
                                            <td class="px-4 py-3 whitespace-nowrap">
                                                @if ($jadwal['teknisi'])
                                                    <span class="font-semibold text-primary-container">{{ $jadwal['teknisi'] }}</span>
                                                @else
                                                    <span class="text-text-secondary">Belum ditugaskan</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1 text-[12px] leading-4 font-semibold whitespace-nowrap {{ $status['kelas'] }}">
                                                    <span class="size-2 rounded-full bg-current" aria-hidden="true"></span>
                                                    {{ $status['label'] }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 text-right">
                                                <a
                                                    @if ($url = $tautan('admin.pesanan.show', $jadwal['id'])) href="{{ $url }}" @else aria-disabled="true" @endif
                                                    class="inline-flex min-h-10 items-center font-semibold text-primary-container hover:underline"
                                                >Detail<span class="sr-only"> kunjungan {{ $jadwal['pelanggan'] }}</span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </section>

                {{-- Pesanan baru --}}
                <section class="{{ $kelasKartu }} p-5" aria-labelledby="judul-pesanan-baru">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle pb-3">
                        <h2 id="judul-pesanan-baru" class="flex items-center gap-2.5 text-[17px] leading-6 font-semibold text-on-surface">
                            <span class="size-2.5 rounded-full bg-status-danger" aria-hidden="true"></span>
                            Pesanan baru
                        </h2>
                        <span class="text-[14px] leading-5 text-text-secondary">{{ count($pesananBaru) }} tertunda</span>
                    </div>

                    @if (count($pesananBaru) === 0)
                        <p class="py-8 text-center text-[14px] leading-5 text-text-secondary">Semua pesanan sudah ditangani.</p>
                    @else
                        <ul class="mt-4 flex flex-col gap-3">
                            @foreach ($pesananBaru as $pesanan)
                                @php($status = $statusPesanan[$pesanan['status']])
                                <li class="flex flex-col gap-3 rounded-xl bg-surface-container-low p-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[13px] leading-[18px] font-bold text-primary-container">#{{ $pesanan['kode'] }}</span>
                                        <span class="text-[14px] leading-5 text-on-surface">{{ $pesanan['ringkasan'] }}</span>
                                        <span class="text-[13px] leading-[18px] font-bold {{ $status['kelas'] }}">{{ $status['label'] }}</span>
                                    </div>
                                    <a
                                        @if ($url = $tautan('admin.pesanan.show', $pesanan['id'])) href="{{ $url }}" @else aria-disabled="true" title="Belum tersedia" @endif
                                        aria-label="{{ $status['aksi'] }} pesanan {{ $pesanan['kode'] }}"
                                        @class([
                                            'flex min-h-10 shrink-0 items-center justify-center rounded-full px-5 text-[14px] leading-5 font-semibold text-white transition-colors duration-200',
                                            'bg-primary-container hover:bg-primary' => $status['varian'] === 'utama',
                                            'bg-secondary hover:bg-on-secondary-fixed-variant' => $status['varian'] === 'gelap',
                                        ])
                                    >{{ $status['aksi'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>

            <div class="flex flex-col gap-4 lg:gap-5">
                {{-- Verifikasi pembayaran --}}
                <section class="{{ $kelasKartu }} p-5" aria-labelledby="judul-verifikasi">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle pb-3">
                        <h2 id="judul-verifikasi" class="flex items-center gap-2.5 text-[17px] leading-6 font-semibold text-on-surface">
                            <span class="size-2.5 rounded-full bg-status-success" aria-hidden="true"></span>
                            Verifikasi pembayaran
                        </h2>
                        @if ($url = $tautan('admin.pembayaran.index'))
                            <a href="{{ $url }}" class="{{ $kelasTautanSemua }}">Semua <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span></a>
                        @endif
                    </div>

                    @if (count($verifikasiPembayaran) === 0)
                        <p class="py-8 text-center text-[14px] leading-5 text-text-secondary">Tidak ada pembayaran yang perlu dicek.</p>
                    @else
                        <ul class="mt-4 flex flex-col gap-3">
                            @foreach ($verifikasiPembayaran as $bayar)
                                <li class="flex flex-col gap-3 rounded-xl bg-surface-container-low p-4">
                                    <div class="flex flex-col gap-1">
                                        <p class="flex flex-wrap items-center gap-2">
                                            <span class="rounded-md bg-lencana-biru-latar px-2 py-0.5 text-[12px] leading-4 font-semibold text-lencana-biru">{{ $bayar['metode'] }}</span>
                                            <span class="text-[14px] leading-5 font-semibold text-on-surface tabular-nums">{{ $rupiah($bayar['jumlah']) }}</span>
                                        </p>
                                        <p class="text-[14px] leading-5 text-text-secondary">{{ $bayar['keterangan'] }} (#{{ $bayar['kode_pesanan'] }})</p>
                                    </div>
                                    <div class="flex flex-wrap gap-2">
                                        <x-admin.tombol-aksi route="admin.pembayaran.setujui" :parameter="$bayar['id']" varian="setuju" class="flex-1 sm:flex-none" :aria-label="'Setujui pembayaran '.$bayar['kode_pesanan']">
                                            Setujui
                                        </x-admin.tombol-aksi>
                                        <x-admin.tombol-aksi route="admin.pembayaran.tolak" :parameter="$bayar['id']" varian="tolak" :aria-label="'Tolak pembayaran '.$bayar['kode_pesanan']">
                                            Tolak
                                        </x-admin.tombol-aksi>
                                        <a
                                            @if ($url = $tautan('admin.pembayaran.show', $bayar['id'])) href="{{ $url }}" @else aria-disabled="true" @endif
                                            aria-label="Lihat bukti bayar {{ $bayar['kode_pesanan'] }}"
                                            title="Lihat bukti bayar"
                                            class="flex size-10 items-center justify-center rounded-xl border border-border-subtle bg-white text-text-primary hover:border-primary-container hover:bg-surface-tint"
                                        >
                                            <span class="material-symbols-outlined text-[20px]" aria-hidden="true">receipt</span>
                                        </a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Stok kritis --}}
                <section class="{{ $kelasKartu }} p-5" aria-labelledby="judul-stok">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle pb-3">
                        <h2 id="judul-stok" class="flex items-center gap-2.5 text-[17px] leading-6 font-semibold text-on-surface">
                            <span class="size-2.5 rounded-full bg-status-danger" aria-hidden="true"></span>
                            Stok kritis
                        </h2>
                        @if ($url = $tautan('admin.produk.index'))
                            <a href="{{ $url }}" class="{{ $kelasTautanSemua }}">Gudang <span class="material-symbols-outlined text-[18px]" aria-hidden="true">arrow_forward</span></a>
                        @endif
                    </div>

                    @if (count($stokKritis) === 0)
                        <p class="py-8 text-center text-[14px] leading-5 text-text-secondary">Semua stok aman.</p>
                    @else
                        <ul class="mt-4 flex flex-col gap-3">
                            @foreach ($stokKritis as $produk)
                                <li class="flex items-center justify-between gap-3 rounded-xl bg-surface-container-low p-4">
                                    <div class="flex min-w-0 flex-col gap-2">
                                        <span class="text-[14px] leading-5 font-semibold text-on-surface">{{ $produk['nama'] }}</span>
                                        <span class="flex items-center gap-3">
                                            <span class="h-2 w-28 overflow-hidden rounded-full bg-surface-container-high" aria-hidden="true">
                                                <span class="block h-full rounded-full bg-status-danger" style="width: {{ min(100, round($produk['stok'] / max(1, $produk['batas']) * 100)) }}%"></span>
                                            </span>
                                            <span class="text-[13px] leading-[18px] font-bold text-lencana-merah">Sisa {{ $produk['stok'] }}</span>
                                        </span>
                                    </div>
                                    <a
                                        @if ($url = $tautan('admin.produk.edit', $produk['id'])) href="{{ $url }}" @else aria-disabled="true" title="Belum tersedia" @endif
                                        aria-label="Restok {{ $produk['nama'] }}"
                                        class="flex min-h-10 shrink-0 items-center gap-1 rounded-xl bg-white px-4 text-[14px] leading-5 font-semibold text-primary-container hover:bg-surface-tint"
                                    >
                                        <span class="material-symbols-outlined text-[20px]" aria-hidden="true">add</span>
                                        Restok
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>

                {{-- Pendaftar teknisi baru --}}
                <section class="{{ $kelasKartu }} p-5" aria-labelledby="judul-pendaftar">
                    <div class="flex items-center justify-between gap-3 border-b border-border-subtle pb-3">
                        <h2 id="judul-pendaftar" class="flex items-center gap-2.5 text-[17px] leading-6 font-semibold text-on-surface">
                            <span class="size-2.5 rounded-full bg-tertiary-container" aria-hidden="true"></span>
                            Pendaftar teknisi baru
                        </h2>
                        <span class="text-[14px] leading-5 text-text-secondary">{{ count($pendaftarTeknisi) }} pendaftar</span>
                    </div>

                    @if (count($pendaftarTeknisi) === 0)
                        <p class="py-8 text-center text-[14px] leading-5 text-text-secondary">Belum ada pendaftar baru.</p>
                    @else
                        <ul class="mt-4 flex flex-col gap-3">
                            @foreach ($pendaftarTeknisi as $pendaftar)
                                <li class="flex flex-col gap-3 rounded-xl bg-surface-container-low p-4">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="text-[14px] leading-5 font-semibold text-on-surface">{{ $pendaftar['nama'] }} ({{ $pendaftar['umur'] }} th)</span>
                                        <span class="text-[14px] leading-5 text-text-secondary">{{ $pendaftar['domisili'] }} &bull; {{ $pendaftar['sertifikasi'] }}</span>
                                    </div>
                                    <div class="flex flex-wrap gap-2 border-t border-border-subtle pt-3">
                                        <x-admin.tombol-aksi route="admin.teknisi.setujui" :parameter="$pendaftar['id']" class="flex-1 sm:flex-none">
                                            Terima
                                        </x-admin.tombol-aksi>
                                        <a
                                            @if ($url = $tautan('admin.teknisi.show', $pendaftar['id'])) href="{{ $url }}" @else aria-disabled="true" title="Belum tersedia" @endif
                                            class="flex min-h-10 items-center justify-center rounded-xl border border-border-subtle bg-white px-4 text-[14px] leading-5 font-semibold text-text-primary hover:border-primary-container hover:bg-surface-tint"
                                        >Tinjau berkas</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </section>
            </div>
        </div>
    </div>
</x-layouts.admin>
