<x-layouts.admin title="Ubah jenis AC">
    {{-- Variabel: $jenisAc --}}
    <div class="mx-auto flex w-full max-w-[720px] flex-col gap-4 lg:gap-5">
        <x-admin.kepala-halaman judul="Ubah jenis AC" :deskripsi="'Perbarui nama atau keterangan '.$jenisAc->nama.'.'" />

        <x-admin.kartu>
            @include('admin.layanan.jenis-ac._form', [
                'jenisAc' => $jenisAc,
                'aksi' => route('admin.layanan.jenis-ac.update', $jenisAc->id_jenis_ac),
                'metode' => 'PUT',
                'labelTombol' => 'Perbarui jenis AC',
            ])
        </x-admin.kartu>
    </div>
</x-layouts.admin>
