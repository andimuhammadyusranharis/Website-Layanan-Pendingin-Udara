<x-layouts.admin title="Tambah jenis AC">
    {{-- Variabel: $aksi, $metode, $labelTombol --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Tambah jenis AC" deskripsi="Jenis AC baru bisa langsung dipakai saat mengisi tarif." />

        <x-admin.kartu>
            @include('admin.layanan.jenis-ac._form', [
                'jenisAc' => null,
                'aksi' => route('admin.layanan.jenis-ac.store'),
                'metode' => 'POST',
                'labelTombol' => 'Simpan jenis AC',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>
