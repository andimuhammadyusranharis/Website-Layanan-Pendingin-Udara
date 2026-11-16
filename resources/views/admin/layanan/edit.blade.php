<x-layouts.admin title="Ubah layanan">
    {{-- Variabel: $layanan (Layanan yang diubah), $opsiKategori --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Ubah layanan" :deskripsi="'Perbarui data layanan '.$layanan->nama.'.'" />

        <x-admin.kartu>
            @include('admin.layanan._form', [
                'layanan' => $layanan,
                'opsiKategori' => $opsiKategori,
                'aksi' => route('admin.layanan.update', $layanan),
                'metode' => 'PUT',
                'labelTombol' => 'Simpan perubahan',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>
