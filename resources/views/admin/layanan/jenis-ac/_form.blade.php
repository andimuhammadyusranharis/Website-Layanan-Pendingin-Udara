{{--
    Formulir jenis AC, dipakai bersama untuk tambah dan ubah.
    Variabel: $jenisAc (hanya pada ubah), $aksi, $metode, $labelTombol.
--}}
@php
    $nama = old('nama', $jenisAc?->nama ?? '');
    $keterangan = old('keterangan', $jenisAc?->keterangan ?? '');
@endphp

<form action="{{ $aksi }}" method="POST" class="flex flex-col gap-5">
    @csrf
    @method($metode)

    <x-admin.isian
        nama="nama"
        label="Nama jenis AC"
        :nilai="$nama"
        wajib
        bantuan="Contoh: Split 1/2 - 1 PK"
    />

    <x-admin.isian
        nama="keterangan"
        label="Keterangan"
        tipe="textarea"
        :nilai="$keterangan"
        bantuan="Penjelasan singkat jenis AC ini, misalnya untuk dinding atau plafon."
    />

    <div class="flex flex-wrap gap-3">
        <button
            type="submit"
            class="inline-flex min-h-11 items-center justify-center gap-2 rounded-full bg-primary-container px-5 text-[14px] leading-5 font-semibold whitespace-nowrap text-white transition-colors duration-200 hover:bg-primary hover:shadow-[0px_8px_20px_rgba(30,99,214,0.25)]"
        >
            {{ $labelTombol }}
        </button>
        <x-admin.tombol-tautan route="admin.layanan.index" :parameter="['tab' => 'jenis_ac']">Batal</x-admin.tombol-tautan>
    </div>
</form>
