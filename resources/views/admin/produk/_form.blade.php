{{-- Variabel: $produk (Produk), $opsiKategori (nilai => label), $aksi (URL tujuan), $metode (POST atau PUT), $labelTombol --}}
@php
    $kelasJudulBagian = 'text-[17px] leading-6 font-bold text-on-surface';
    $kelasKotak = 'flex items-center gap-3 text-[15px] leading-6 text-text-primary';
    $kelasCentang = 'size-5 rounded border-border-subtle text-primary-container focus:ring-primary-container/30';
    $adaFoto = $produk->url_gambar && is_file(public_path($produk->url_gambar));
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
        <x-admin.isian nama="nama" label="Nama produk" :nilai="$produk->nama" wajib bantuan="Untuk unit AC, tulis seri dan kapasitasnya saja, misalnya Flash Inverter 0,5 PK. Merek diisi terpisah." />
        <x-admin.isian nama="kategori" label="Kategori" tipe="select" :opsi="$opsiKategori" :nilai="$produk->kategori?->value" wajib bantuan="Kategori menjadi tombol saring di halaman Toko." />
        <x-admin.isian nama="deskripsi" label="Deskripsi" tipe="textarea" :baris="4" :nilai="$produk->deskripsi" wajib bantuan="Kegunaan dan kecocokan barang, maksimal 3000 huruf." />
    </section>

    {{-- Khusus unit AC --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-unit-ac">
        <div class="flex flex-col gap-1">
            <h2 id="bagian-unit-ac" class="{{ $kelasJudulBagian }}">Khusus unit AC</h2>
            <p class="text-[14px] leading-5 text-text-secondary">Diisi hanya bila kategorinya Unit AC baru. Untuk kategori lain, isian di bagian ini dikosongkan saat disimpan.</p>
        </div>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.isian nama="merek" label="Merek" :nilai="$produk->merek" bantuan="Contoh: Daikin. Wajib untuk unit AC." />
            <x-admin.isian nama="model" label="Model" :nilai="$produk->model" bantuan="Kode model, misalnya FTKQ15UVM4." />
        </div>
        <x-admin.isian nama="spesifikasi" label="Spesifikasi" tipe="textarea" :baris="3" :nilai="$produk->spesifikasi" bantuan="Satu poin per baris, maksimal 6 baris. Contoh: Daya 340 watt." />
    </section>

    {{-- Harga dan stok --}}
    <section class="flex flex-col gap-5" aria-labelledby="bagian-harga">
        <h2 id="bagian-harga" class="{{ $kelasJudulBagian }}">Harga dan stok</h2>
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
            <x-admin.isian nama="harga" label="Harga (Rp)" tipe="number" :nilai="$produk->harga" wajib bantuan="Isi angka saja, misalnya 85000." />
            <x-admin.isian nama="stok" label="Stok" tipe="number" :nilai="$produk->stok" wajib bantuan="Jumlah barang yang siap dijual." />
            <x-admin.isian nama="satuan" label="Satuan" :nilai="$produk->satuan" bantuan="Contoh: pasang atau roll (5 m). Boleh kosong." />
        </div>
    </section>

    {{-- Foto --}}
    <section class="flex flex-col gap-4" aria-labelledby="bagian-foto">
        <h2 id="bagian-foto" class="{{ $kelasJudulBagian }}">Foto</h2>

        @if ($adaFoto)
            <div class="flex flex-col gap-3 sm:flex-row sm:items-center">
                <img src="{{ asset($produk->url_gambar) }}" alt="Foto produk {{ $produk->nama }} saat ini" class="aspect-[8/5] w-full max-w-[240px] rounded-xl border border-border-subtle object-cover" />
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
        <label class="{{ $kelasKotak }}">
            <input type="checkbox" name="aktif" value="1" @checked(old('aktif', $produk->aktif)) class="{{ $kelasCentang }}" />
            Produk aktif dan tampil di halaman Toko
        </label>
    </section>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-full bg-primary-container px-6 text-[14px] leading-5 font-semibold text-white transition-colors duration-200 hover:bg-primary">{{ $labelTombol }}</button>
        <x-admin.tombol-tautan route="admin.produk.index">Batal</x-admin.tombol-tautan>
    </div>
</form>