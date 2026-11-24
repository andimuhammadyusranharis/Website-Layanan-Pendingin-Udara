<x-layouts.admin title="Ubah produk">
    {{-- Variabel: $produk (Produk yang diubah), $opsiKategori --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Ubah produk" :deskripsi="'Perbarui data produk '.$produk->nama.'.'" />

        <x-admin.kartu>
            @include('admin.produk._form', [
                'produk' => $produk,
                'opsiKategori' => $opsiKategori,
                'aksi' => route('admin.produk.update', $produk),
                'metode' => 'PUT',
                'labelTombol' => 'Simpan perubahan',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>