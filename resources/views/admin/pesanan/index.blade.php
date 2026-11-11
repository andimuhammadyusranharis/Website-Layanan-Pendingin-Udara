<x-layouts.admin title="Pesanan">
    {{-- Variabel: $pesanan (paginator), $filter, $pilihanStatus, $pilihanSumber, $pilihanTanggal --}}
    @php
        $warnaStatus = [
            'menunggu' => 'netral',
            'dijadwalkan' => 'biru',
            'dikerjakan' => 'kuning',
            'dikirim' => 'sian',
            'selesai' => 'hijau',
            'dibatalkan' => 'merah',
        ];
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Pesanan" deskripsi="Semua pesanan dari online, toko, dan telepon/WA.">
            <x-slot:aksi>
                <x-admin.tombol-tautan route="admin.pesanan.create" varian="utama" ikon="add_circle">Pesanan manual</x-admin.tombol-tautan>
            </x-slot:aksi>
        </x-admin.kepala-halaman>

        <x-admin.kartu aria-labelledby="judul-daftar-pesanan">
            <div class="flex items-center justify-between gap-3 pb-4">
                <h2 id="judul-daftar-pesanan" class="flex items-center gap-2.5 text-[19px] leading-7 font-semibold text-on-surface">
                    <span class="size-2.5 rounded-full bg-primary-container" aria-hidden="true"></span>
                    Daftar pesanan
                </h2>
                <span class="text-[14px] leading-5 text-text-secondary">{{ $pesanan->total() }} pesanan</span>
            </div>

            <x-admin.filter
                route="admin.pesanan.index"
                placeholder="Cari nama pelanggan atau nomor pesanan"
                :nilai="$filter"
                :pilihan="[
                    ['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus],
                    ['nama' => 'sumber', 'label' => 'Sumber', 'semua' => 'Semua', 'opsi' => $pilihanSumber],
                    ['nama' => 'tanggal', 'label' => 'Tanggal', 'semua' => 'Semua', 'opsi' => $pilihanTanggal],
                ]"
            />

            @if ($pesanan->isEmpty())
                <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                    <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">receipt_long</span>
                    <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada pesanan yang cocok.</p>
                    <a href="{{ route('admin.pesanan.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian dan filter</a>
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="text-[14px] leading-5 font-semibold text-text-secondary">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Pesanan</th>
                                <th scope="col" class="px-4 py-3.5">Layanan / produk</th>
                                <th scope="col" class="px-4 py-3.5">Tanggal</th>
                                <th scope="col" class="px-4 py-3.5">Sumber</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary">
                            @foreach ($pesanan as $item)
                                <tr>
                                    <td class="px-4 py-3.5">
                                        <span class="block font-semibold text-on-surface">{{ $item['pelanggan'] }}</span>
                                        <span class="block text-[13px] leading-[18px] tracking-wide text-text-secondary">#{{ $item['kode'] }}</span>
                                    </td>
                                    <td class="px-4 py-3.5">{{ $item['ringkasan'] }}</td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        <time datetime="{{ $item['tanggal'] }}">{{ Carbon\Carbon::parse($item['tanggal'])->locale('id')->translatedFormat('j M Y') }}</time>
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">{{ $pilihanSumber[$item['sumber']] }}</td>
                                    <td class="px-4 py-3.5">
                                        <x-admin.lencana :warna="$warnaStatus[$item['status']]">{{ $pilihanStatus[$item['status']] }}</x-admin.lencana>
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <x-admin.tombol-tautan route="admin.pesanan.show" :parameter="$item['id']" :aria-label="'Detail pesanan '.$item['kode']">Detail</x-admin.tombol-tautan>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <x-admin.paginasi
                :data="$pesanan"
                :keterangan="$pesanan->total() > 0 ? 'Menampilkan '.$pesanan->firstItem().'-'.$pesanan->lastItem().' dari '.$pesanan->total().' pesanan' : 'Menampilkan 0 pesanan'"
            />
        </x-admin.kartu>
    </div>
</x-layouts.admin>
