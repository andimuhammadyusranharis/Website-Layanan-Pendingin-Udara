<x-layouts.admin title="Produk & Stok">
    {{-- Variabel: $jumlahProduk, $produk (Collection Produk), $filter, $pilihanStatus --}}
    @php
        $rupiah = fn (int $nilai): string => 'Rp '.number_format($nilai, 0, ',', '.');
        $kelasKepalaTabel = 'text-[14px] leading-5 font-semibold text-text-secondary';
        $kelasIsiTabel = 'divide-y divide-border-subtle border-b border-border-subtle text-[15px] leading-6 text-text-primary';
    @endphp

    <div class="mx-auto flex w-full max-w-[1280px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Produk & Stok" :deskripsi="'Atur barang yang dijual di halaman Toko. Saat ini ada '.$jumlahProduk.' produk.'">
            <x-slot:aksi>
                <x-admin.tombol-tautan route="admin.produk.create" varian="utama" ikon="add_circle">Tambah produk</x-admin.tombol-tautan>
            </x-slot:aksi>
        </x-admin.kepala-halaman>

        <x-admin.kartu>
            <x-admin.filter
                route="admin.produk.index"
                placeholder="Cari nama, merek, atau model"
                :nilai="$filter"
                :pilihan="[['nama' => 'status', 'label' => 'Status', 'semua' => 'Semua', 'opsi' => $pilihanStatus]]"
            />

            @if (count($produk) === 0)
                <div class="mt-4 flex flex-col items-center gap-2 rounded-xl border border-dashed border-border-subtle px-4 py-12 text-center">
                    <span class="material-symbols-outlined text-[36px] text-outline" aria-hidden="true">inventory_2</span>
                    <p class="text-[15px] leading-6 font-semibold text-on-surface">Tidak ada produk yang cocok.</p>
                    <a href="{{ route('admin.produk.index') }}" class="text-[14px] leading-5 font-semibold text-primary-container hover:underline">Hapus pencarian dan filter</a>
                </div>
            @else
                <div class="mt-4 overflow-x-auto">
                    <table class="w-full min-w-[820px] text-left">
                        <thead class="{{ $kelasKepalaTabel }}">
                            <tr class="bg-surface-container-low">
                                <th scope="col" class="rounded-l-xl px-4 py-3.5">Produk</th>
                                <th scope="col" class="px-4 py-3.5">Harga</th>
                                <th scope="col" class="px-4 py-3.5">Stok</th>
                                <th scope="col" class="px-4 py-3.5">Status</th>
                                <th scope="col" class="rounded-r-xl px-4 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="{{ $kelasIsiTabel }}">
                            @foreach ($produk as $item)
                                <tr>
                                    <td class="max-w-[320px] px-4 py-3.5">
                                        <div class="flex items-center gap-3">
                                            @if ($item->url_gambar && is_file(public_path($item->url_gambar)))
                                                <img src="{{ asset($item->url_gambar) }}" alt="" loading="lazy" class="size-12 shrink-0 rounded-lg border border-border-subtle object-cover" />
                                            @else
                                                <span class="flex size-12 shrink-0 items-center justify-center rounded-lg bg-surface-tint text-primary-container" title="Belum ada foto">
                                                    <span class="material-symbols-outlined text-[24px]" aria-hidden="true">inventory_2</span>
                                                </span>
                                            @endif
                                            <div class="min-w-0">
                                                <span class="block text-[16px] font-semibold text-on-surface">{{ $item->nama }}</span>
                                                @if ($item->merek)
                                                    <span class="block text-[14px] leading-5 text-text-secondary">{{ $item->merek }} {{ $item->model }}</span>
                                                @endif
                                                <span class="block text-[13px] leading-[18px] text-text-secondary">{{ $item->kategori->label() }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3.5 text-[17px] font-bold whitespace-nowrap text-on-secondary-fixed-variant tabular-nums">
                                        {{ $rupiah($item->harga) }}
                                        @if ($item->satuan)
                                            <span class="block text-[13px] leading-[18px] font-normal text-text-secondary">per {{ $item->satuan }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5 whitespace-nowrap">
                                        @if ($item->stok <= 0)
                                            <x-admin.lencana warna="merah">Habis</x-admin.lencana>
                                        @elseif ($item->stok <= \App\Services\ProdukService::BATAS_STOK_MENIPIS)
                                            <x-admin.lencana warna="kuning">Sisa {{ $item->stok }}</x-admin.lencana>
                                        @else
                                            <span class="text-[16px] tabular-nums">{{ $item->stok }}</span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        @if ($item->aktif)
                                            <x-admin.lencana warna="hijau">Aktif</x-admin.lencana>
                                        @else
                                            <x-admin.lencana warna="netral">Nonaktif</x-admin.lencana>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3.5">
                                        <div class="flex gap-2">
                                            <x-admin.tombol-tautan route="admin.produk.edit" :parameter="$item->id_produk" :aria-label="'Ubah produk '.$item->nama">Ubah</x-admin.tombol-tautan>
                                            <x-admin.tombol-aksi
                                                route="admin.produk.destroy"
                                                :parameter="$item->id_produk"
                                                metode="DELETE"
                                                varian="tolak"
                                                :konfirmasi="'Hapus produk '.$item->nama.'? Tindakan ini tidak bisa dibatalkan.'"
                                                judul-konfirmasi="Hapus produk?"
                                                :aria-label="'Hapus produk '.$item->nama"
                                            >Hapus</x-admin.tombol-aksi>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-admin.kartu>
    </div>
</x-layouts.admin>