<x-layouts.admin title="Jadwal">
    {{-- Variabel: $jadwal (paginator), $filter, $pilihanTanggal, $pilihanTeknisi, $pilihanStatus --}}
    @php
        $warnaStatus = ['dijadwalkan' => 'biru', 'dikerjakan' => 'kuning', 'selesai' => 'hijau'];

        $tanggalTampil = match ($filter['tanggal']) {
            'besok' => today()->addDay()->locale('id')->translatedFormat('l, j F Y'),
            '7_hari' => today()->locale('id')->translatedFormat('j M').' - '.today()->addDays(6)->locale('id')->translatedFormat('j M Y'),
            default => today()->locale('id')->translatedFormat('l, j F Y'),
        };

        $satuanKeterangan = mb_strtolower($pilihanTanggal[$filter['tanggal']]);
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Jadwal" deskripsi="Jadwal kunjungan teknisi ke rumah pelanggan.">
            <x-slot:aksi>
                <x-admin.tombol-tautan route="admin.jadwal.create" varian="utama" ikon="add_circle">Jadwalkan pesanan</x-admin.tombol-tautan>
            </x-slot:aksi>
        </x-admin.kepala-halaman>

        <x-admin.kartu aria-labelledby="judul-jadwal-kunjungan">
            <div class="flex flex-wrap items-center justify-between gap-3 pb-4">
                <h2 id="judul-jadwal-kunjungan" class="flex items-center gap-2.5 text-[19px] leading-7 font-semibold text-on-surface">
                    <span class="size-2.5 rounded-full bg-primary-container" aria-hidden="true"></span>
                    Jadwal kunjungan
                </h2>
                <span class="text-[14px] leading-5 text-text-secondary">{{ $tanggalTampil }}</span>
            </div>

            <x-admin.filter
                route="admin.jadwal.index"
                placeholder="Cari nama pelanggan atau alamat"
                :nilai="$filter"
                :pilihan="[
                    ['nama' => 'tanggal', 'label' => 'Tanggal', 'opsi' => $pilihanTanggal],
                    ['nama' => 'teknisi', 'label' => 'Teknisi', 'semua' => 'Semua', 'opsi' => $pilihanTeknisi],
                    ['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus],
                ]"
            />

            @if ($jadwal->isEmpty())
                <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                    <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">event_available</span>
                    <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada kunjungan untuk pilihan ini.</p>
                    <a href="{{ route('admin.jadwal.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Lihat jadwal hari ini</a>
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[880px] text-left">
                        <thead class="text-[14px] leading-5 font-semibold text-text-secondary">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Waktu</th>
                                <th scope="col" class="px-4 py-3.5">Pelanggan &amp; alamat</th>
                                <th scope="col" class="px-4 py-3.5">Layanan</th>
                                <th scope="col" class="px-4 py-3.5">Teknisi</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary">
                            @foreach ($jadwal as $item)
                                <tr>
                                    <td class="px-4 py-3.5 font-semibold whitespace-nowrap text-on-surface tabular-nums">
                                        @if ($filter['tanggal'] !== 'hari_ini')
                                            <span class="block text-[13px] leading-[18px] font-normal text-text-secondary">{{ Carbon\Carbon::parse($item['tanggal'])->locale('id')->translatedFormat('D, j M') }}</span>
                                        @endif
                                        {{ $item['mulai'] }}-{{ $item['selesai'] }}
                                    </td>
                                    <td class="max-w-[260px] px-4 py-3.5">
                                        <span class="block font-semibold text-on-surface">{{ $item['pelanggan'] }}</span>
                                        <span class="block text-[13px] leading-[18px] text-text-secondary">{{ $item['alamat'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">{{ $item['layanan'] }}</td>
                                    <td class="px-4 py-3.5 font-semibold whitespace-nowrap text-primary-container">{{ $item['teknisi'] }}</td>
                                    <td class="px-4 py-3.5">
                                        <x-admin.lencana :warna="$warnaStatus[$item['status']]">{{ $pilihanStatus[$item['status']] }}</x-admin.lencana>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <x-admin.tombol-tautan route="admin.jadwal.edit" :parameter="$item['id']" :aria-label="'Ubah jadwal '.$item['pelanggan']">Ubah</x-admin.tombol-tautan>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <x-admin.paginasi :data="$jadwal" :keterangan="'Menampilkan '.$jadwal->count().' dari '.$jadwal->total().' kunjungan '.$satuanKeterangan" />
        </x-admin.kartu>
    </div>
</x-layouts.admin>
