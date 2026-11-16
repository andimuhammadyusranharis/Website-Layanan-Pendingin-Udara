{{-- Variabel: $layanan (Layanan), $opsiKategori (nilai => label), $aksi (URL tujuan), $metode (POST atau PUT), $labelTombol --}}
@php
    $kelasJudulBagian = 'text-[17px] leading-6 font-bold text-on-surface';
    $kelasKotak = 'flex items-center gap-3 text-[15px] leading-6 text-text-primary';
    $kelasCentang = 'size-5 rounded border-border-subtle text-primary-container focus:ring-primary-container/30';
    $adaFoto = $layanan->url_gambar && is_file(public_path($layanan->url_gambar));
    $adaGalatFoto = $errors->has('gambar');
@endphp

<form method="POST" action="{{ $aksi }}" enctype="multipart/form-data" class="flex flex-col gap-8" novalidate>
    @csrf
    @if ($metode !== 'POST')
        @method($metode)
    @endif

    {{-- Informasi dasar --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-dasar">
        <h2 id="bagian-dasar" class="{{ $kelasJudulBagian }}">Informasi dasar</h2>
        <x-admin.isian nama="nama" label="Nama layanan" :nilai="$layanan->nama" wajib />
        <x-admin.isian nama="kategori" label="Kategori" tipe="select" :opsi="$opsiKategori" :nilai="$layanan->kategori?->value" wajib bantuan="Kategori menjadi tombol saring di halaman layanan pelanggan." />
        <x-admin.isian nama="ringkasan" label="Ringkasan untuk kartu" :nilai="$layanan->ringkasan" wajib bantuan="Satu atau dua kalimat pendek, maksimal 160 huruf. Tampil di daftar layanan." />
    </section>

    {{-- Harga --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-harga">
        <h2 id="bagian-harga" class="{{ $kelasJudulBagian }}">Harga</h2>
        <label class="{{ $kelasKotak }}">
            <input type="checkbox" name="perlu_survei" value="1" @checked(old('perlu_survei', $layanan->perlu_survei)) class="{{ $kelasCentang }}" />
            Perlu survei dulu (harga tidak ditampilkan, diganti "Setelah survei")
        </label>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.isian nama="tarif_mulai" label="Tarif mulai (Rp)" tipe="number" :nilai="$layanan->tarif_mulai" bantuan="Isi angka saja, misalnya 75000. Boleh kosong bila perlu survei." />
            <x-admin.isian nama="satuan" label="Satuan" :nilai="$layanan->satuan" bantuan="Contoh: unit atau paket. Boleh kosong." />
            <x-admin.isian nama="keterangan_harga" label="Keterangan harga" :nilai="$layanan->keterangan_harga" wajib bantuan="Teks di atas harga, misalnya Mulai dari atau Biaya pengecekan." />
            <x-admin.isian nama="catatan_harga" label="Catatan harga" :nilai="$layanan->catatan_harga" bantuan="Contoh: Belum termasuk suku cadang. Boleh kosong." />
        </div>
    </section>

    {{-- Detail untuk halaman pelanggan --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-detail">
        <h2 id="bagian-detail" class="{{ $kelasJudulBagian }}">Detail layanan</h2>
        <x-admin.isian nama="deskripsi" label="Deskripsi lengkap" tipe="textarea" :baris="5" :nilai="$layanan->deskripsi" wajib bantuan="Penjelasan layanan untuk halaman detail, maksimal 3000 huruf." />
        <x-admin.isian nama="fitur" label="Apa saja yang dikerjakan" tipe="textarea" :baris="5" :nilai="$layanan->fitur" bantuan="Satu poin per baris, maksimal 8 poin. Contoh: Filter dan kipas dicuci." />
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.isian nama="durasi" label="Lama pengerjaan" :nilai="$layanan->durasi" bantuan="Contoh: 45 sampai 60 menit. Boleh kosong." />
            <x-admin.isian nama="jenis_ac" label="Jenis AC" :nilai="$layanan->jenis_ac" bantuan="Contoh: Split dinding 0,5 sampai 2,5 PK. Boleh kosong." />
        </div>
    </section>

    {{-- Foto --}}
    <section class="flex flex-col gap-4" aria-labelledby="bagian-foto">
        <h2 id="bagian-foto" class="{{ $kelasJudulBagian }}">Foto</h2>

        @if ($adaFoto)
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <img src="{{ asset($layanan->url_gambar) }}" alt="Foto layanan {{ $layanan->nama }} saat ini" class="aspect-[8/5] w-full max-w-[240px] rounded-xl border border-border-subtle object-cover" />
                <label class="{{ $kelasKotak }}">
                    <input type="checkbox" name="hapus_gambar" value="1" @checked(old('hapus_gambar')) class="{{ $kelasCentang }}" />
                    Hapus foto ini
                </label>
            </div>
        @endif

        <div class="flex flex-col gap-2">
            <label for="kolom-gambar" class="text-[15px] leading-6 font-semibold text-text-primary">{{ $adaFoto ? 'Ganti foto' : 'Unggah foto' }}</label>
            <input
                id="kolom-gambar"
                name="gambar"
                type="file"
                accept="image/jpeg,image/png,image/webp"
                @if ($adaGalatFoto) aria-invalid="true" aria-describedby="kolom-gambar-galat" @else aria-describedby="kolom-gambar-bantuan" @endif
                class="block w-full rounded-xl border border-border-subtle bg-white p-2 text-[15px] leading-6 text-text-primary file:mr-3 file:rounded-lg file:border-0 file:bg-surface-tint file:px-4 file:py-2 file:text-[14px] file:font-semibold file:text-on-secondary-fixed-variant"
            />
            @if ($adaGalatFoto)
                <p id="kolom-gambar-galat" class="flex items-start gap-1.5 text-[14px] leading-5 text-status-danger">
                    <span class="material-symbols-outlined text-[18px]" aria-hidden="true">error</span>
                    {{ $errors->first('gambar') }}
                </p>
            @else
                <p id="kolom-gambar-bantuan" class="text-[14px] leading-5 text-text-secondary">JPG, PNG, atau WebP, maksimal 2 MB. Disarankan 1200 x 750 piksel (rasio 8:5) agar tidak terpotong dan halaman tetap ringan.</p>
            @endif
        </div>
    </section>

    {{-- Pengaturan --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-pengaturan">
        <h2 id="bagian-pengaturan" class="{{ $kelasJudulBagian }}">Pengaturan</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.isian nama="bagi_hasil" label="Bagi hasil teknisi (%)" tipe="number" :nilai="$layanan->bagi_hasil" wajib bantuan="Persentase dari tarif untuk teknisi, 0 sampai 100." />
            <x-admin.isian nama="urutan" label="Urutan tampil" tipe="number" :nilai="$layanan->urutan" bantuan="Angka kecil tampil lebih dulu di halaman pelanggan." />
        </div>
        <label class="{{ $kelasKotak }}">
            <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $layanan->aktif)) class="{{ $kelasCentang }}" />
            Layanan aktif dan tampil untuk pelanggan
        </label>
    </section>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-full bg-primary-container px-6 text-[14px] leading-5 font-semibold text-white transition-colors duration-200 hover:bg-primary">{{ $labelTombol }}</button>
        <x-admin.tombol-tautan route="admin.layanan.index">Batal</x-admin.tombol-tautan>
    </div>
</form>
