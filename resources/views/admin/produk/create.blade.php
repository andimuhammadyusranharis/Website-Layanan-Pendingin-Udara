<x-layouts.admin title="Tambah produk">
    {{-- Variabel: $produk (Produk baru berisi nilai awal), $opsiKategori --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Tambah produk" deskripsi="Produk aktif langsung tampil di halaman Toko setelah disimpan." />

        <x-admin.kartu>
            @include('admin.produk._form', [
                'produk' => $produk,
                'opsiKategori' => $opsiKategori,
                'aksi' => route('admin.produk.store'),
                'metode' => 'POST',
                'labelTombol' => 'Simpan produk',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>