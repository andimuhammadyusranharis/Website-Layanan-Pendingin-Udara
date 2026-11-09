<x-layouts.admin title="Pembayaran">
    {{-- Variabel: $tab, $jumlahPerluVerifikasi, $pembayaran (paginator), $filter, $pilihanMetode, $pilihanTanggal --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp '.number_format($nilai, 0, ',', '.');
        $waktu = fn (string $nilai): string => Carbon\Carbon::parse($nilai)->locale('id')->translatedFormat('j M, H:i');

        $kelasMetode = [
            'transfer_bri' => 'bg-lencana-biru-latar text-lencana-biru',
            'qris_toko' => 'bg-lencana-hijau-latar text-lencana-hijau',
            'tunai' => 'bg-lencana-netral-latar text-lencana-netral',
        ];

        $statusBayar = [
            'menunggu' => ['label' => 'Perlu dicek', 'warna' => 'kuning'],
            'disetujui' => ['label' => 'Disetujui', 'warna' => 'hijau'],
            'ditolak' => ['label' => 'Ditolak', 'warna' => 'merah'],
        ];

        $keterangan = [
            'verifikasi' => 'pembayaran yang perlu dicek',
            'semua' => 'pembayaran',
            'setoran' => 'setoran tunai',
        ][$tab];
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Pembayaran" deskripsi="Cek bukti transfer dan catat setoran tunai dari teknisi.">
            <x-slot:aksi>
                <x-admin.tombol-tautan route="admin.pembayaran.setoran.create" varian="utama" ikon="add_circle">Catat setoran tunai</x-admin.tombol-tautan>
            </x-slot:aksi>
        </x-admin.kepala-halaman>

        <x-admin.kartu>
            <x-admin.tab
                route="admin.pembayaran.index"
                :aktif="$tab"
                :tab="['verifikasi' => 'Perlu verifikasi ('.$jumlahPerluVerifikasi.')', 'semua' => 'Semua pembayaran', 'setoran' => 'Setoran tunai']"
            />

            <x-admin.filter
                class="mt-4"
                route="admin.pembayaran.index"
                :placeholder="$tab === 'setoran' ? 'Cari nama teknisi, pelanggan, atau nomor pesanan' : 'Cari nama pelanggan atau nomor pesanan'"
                :nilai="$filter"
                :tetap="$tab === 'verifikasi' ? [] : ['tab' => $tab]"
                :pilihan="array_values(array_filter([
                    $tab === 'setoran' ? null : ['nama' => 'metode', 'label' => 'Metode', 'semua' => 'Semua', 'opsi' => $pilihanMetode],
                    ['nama' => 'tanggal', 'label' => 'Tanggal', 'semua' => 'Semua', 'opsi' => $pilihanTanggal],
                ]))"
            />

            @if ($pembayaran->isEmpty())
                <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                    <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">task_alt</span>
                    <p class="text-[15px] leading-6 font-semibold text-on-surface">
                        {{ $tab === 'verifikasi' && $filter === ['cari' => '', 'metode' => '', 'tanggal' => ''] ? 'Semua pembayaran sudah dicek.' : 'Tidak ada data yang cocok.' }}
                    </p>
                </div>
            @elseif ($tab === 'setoran')
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[760px] text-left">
                        <thead class="text-[14px] leading-5 font-semibold text-text-secondary">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Teknisi</th>
                                <th scope="col" class="px-4 py-3.5">Pesanan</th>
                                <th scope="col" class="px-4 py-3.5">Jumlah</th>
                                <th scope="col" class="px-4 py-3.5">Diterima</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary">
                            @foreach ($pembayaran as $item)
                                <tr>
                                    <td class="px-4 py-3.5 font-semibold whitespace-nowrap text-primary-container">{{ $item['teknisi'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="block font-semibold text-on-surface">{{ $item['pelanggan'] }}</span>
                                        <span class="block text-[13px] leading-[18px] text-text-secondary">#{{ $item['kode_pesanan'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-[16px] font-bold whitespace-nowrap text-on-secondary-fixed-variant tabular-nums">{{ $rupiah($item['jumlah']) }}</td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">{{ $waktu($item['waktu']) }}</td>
                                    <td class="px-4 py-3.5">
                                        @if ($item['status'] === 'disetor')
                                            <x-admin.lencana warna="hijau">Sudah disetor</x-admin.lencana>
                                        @else
                                            <x-admin.lencana warna="kuning">Belum disetor</x-admin.lencana>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($item['status'] === 'belum_disetor')
                                            <x-admin.tombol-aksi route="admin.pembayaran.setoran.terima" :parameter="$item['id']" varian="setuju" :aria-label="'Tandai setoran '.$item['kode_pesanan'].' diterima'">Terima setoran</x-admin.tombol-aksi>
                                        @else
                                            <span class="text-[14px] leading-5 text-text-secondary">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="text-[14px] leading-5 font-semibold text-text-secondary">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Pesanan</th>
                                <th scope="col" class="px-4 py-3.5">Jumlah</th>
                                <th scope="col" class="px-4 py-3.5">Metode &amp; bukti</th>
                                <th scope="col" class="px-4 py-3.5">Waktu bayar</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">{{ $tab === 'verifikasi' ? 'Aksi' : 'Status' }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary">
                            @foreach ($pembayaran as $item)
                                <tr>
                                    <td class="max-w-[260px] px-4 py-3.5">
                                        <span class="block font-semibold text-on-surface">{{ $item['pelanggan'] }}</span>
                                        <span class="block text-[13px] leading-[18px] text-text-secondary">#{{ $item['kode_pesanan'] }} &middot; {{ $item['layanan'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5 text-[16px] font-bold whitespace-nowrap text-on-secondary-fixed-variant tabular-nums">{{ $rupiah($item['jumlah']) }}</td>
                                    <td class="px-4 py-3.5">
                                        <span class="inline-block rounded-md px-2 py-0.5 text-[13px] leading-[18px] font-semibold whitespace-nowrap {{ $kelasMetode[$item['metode']] }}">{{ $pilihanMetode[$item['metode']] }}</span>
                                        @if ($item['bukti'])
                                            @if (Route::has('admin.pembayaran.show'))
                                                <a href="{{ route('admin.pembayaran.show', $item['id']) }}" class="mt-0.5 block text-[14px] leading-5 font-semibold text-on-secondary-fixed-variant underline">Lihat bukti</a>
                                            @else
                                                <span class="mt-0.5 block text-[14px] leading-5 font-semibold text-on-secondary-fixed-variant underline" title="Belum tersedia">Lihat bukti</span>
                                            @endif
                                        @else
                                            <span class="mt-0.5 block text-[13px] leading-[18px] text-text-secondary">Tanpa bukti</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">{{ $waktu($item['waktu']) }}</td>
                                    <td class="px-4 py-3.5">
                                        @if ($item['status'] === 'menunggu' && $tab === 'verifikasi')
                                            <div class="flex gap-2">
                                                <x-admin.tombol-aksi route="admin.pembayaran.setujui" :parameter="$item['id']" varian="setuju" :aria-label="'Setujui pembayaran '.$item['kode_pesanan']">Setujui</x-admin.tombol-aksi>
                                                <x-admin.tombol-aksi route="admin.pembayaran.tolak" :parameter="$item['id']" varian="tolak" :konfirmasi="'Tolak pembayaran '.$item['kode_pesanan'].'?'" :aria-label="'Tolak pembayaran '.$item['kode_pesanan']">Tolak</x-admin.tombol-aksi>
                                            </div>
                                        @else
                                            <x-admin.lencana :warna="$statusBayar[$item['status']]['warna']">{{ $statusBayar[$item['status']]['label'] }}</x-admin.lencana>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <x-admin.paginasi :data="$pembayaran" :keterangan="'Menampilkan '.$pembayaran->count().' dari '.$pembayaran->total().' '.$keterangan" />
        </x-admin.kartu>
    </div>
</x-layouts.admin>
