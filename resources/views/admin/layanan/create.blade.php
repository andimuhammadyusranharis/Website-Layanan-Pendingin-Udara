<x-layouts.admin title="Tambah layanan">
    {{-- Variabel: $layanan (Layanan baru berisi nilai awal), $opsiKategori --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Tambah layanan" deskripsi="Layanan baru langsung tampil di daftar setelah disimpan." />

        <x-admin.kartu>
            @include('admin.layanan._form', [
                'layanan' => $layanan,
                'opsiKategori' => $opsiKategori,
                'aksi' => route('admin.layanan.store'),
                'metode' => 'POST',
                'labelTombol' => 'Simpan layanan',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>
