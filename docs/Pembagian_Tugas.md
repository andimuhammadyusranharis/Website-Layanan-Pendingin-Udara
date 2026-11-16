# Pembagian Tugas dan Panduan Bantuan AI

Dokumen ini membagi pekerjaan yang belum selesai ke seluruh anggota tim. Cara kerja Git dan Pull Request ada di [Kontribusi.md](Kontribusi.md). Kebutuhan lengkap ada di [PRD](PRD_Website_CV__Tiga_Putra_Teknik.md).

## 1. Prinsip Pembagian

Setiap anggota memegang **satu modul penuh**: tabel database, model, service, validasi, controller, tampilan, dan tes. Alasannya, penilaian asistensi Minggu 12 sampai 16 meminta setiap mahasiswa menunjukkan kode OOP buatannya sendiri, dengan riwayat commit atas nama sendiri, dan menjelaskannya langsung di depan dosen.

Modul pertama sudah dikerjakan koordinator sebagai contoh: **Layanan**. Semua tugas di bawah meniru pola yang sama. Baca modul itu dulu sebelum mulai.

### Rujukan pola (modul Layanan)

| Lapisan | File |
|---|---|
| Migration | `database/migrations/2026_11_16_131424_create_layanan_table.php` |
| Model | `app/Models/Layanan.php` |
| Enum | `app/Enums/KategoriLayanan.php` |
| Service | `app/Services/LayananService.php` |
| Validasi | `app/Http/Requests/Admin/LayananRequest.php` |
| Controller admin | `app/Http/Controllers/Admin/LayananController.php` |
| Controller publik | `app/Http/Controllers/LayananController.php` |
| Factory dan seeder | `database/factories/LayananFactory.php`, `database/seeders/LayananSeeder.php` |
| Tampilan admin | `resources/views/admin/layanan/` (`index`, `create`, `edit`, `_form`) |
| Tampilan publik | `resources/views/pengguna/layanan.blade.php`, `layanan-detail.blade.php` |
| Tes | `tests/Feature/CrudLayananTest.php` |
| Komponen admin siap pakai | `x-admin.isian`, `x-admin.dialog-konfirmasi`, `x-admin.notifikasi`, `x-admin.tombol-aksi`, `x-admin.tombol-tautan`, `x-admin.kartu`, `x-admin.kepala-halaman`, `x-admin.filter`, `x-admin.lencana` |

## 2. Daftar Tugas

Akun GitHub: Atta = `bleme31`, Pascal = `PascalAncelo`, Fira = `firayanti`, Harry = `Alam2250`.

### Tahap 1: mulai sekarang, tidak saling bergantung

| Kode | Modul | Pemegang | Branch | Label |
|---|---|---|---|---|
| T1 | Produk dan stok | Atta | `fitur/produk` | `backend`, `admin`, `pelanggan`, `P1` |
| T2 | Jenis AC, tarif, dan estimasi biaya | Pascal | `fitur/tarif-estimasi` | `backend`, `admin`, `pelanggan`, `P1` |
| T3 | Ulasan dan halaman statis | Fira | `fitur/ulasan` | `backend`, `admin`, `pelanggan`, `P2` |
| T4 | Akun dan pengguna | Harry | `fitur/akun-pengguna` | `backend`, `admin`, `pelanggan`, `P1` |

### Tahap 2: dimulai setelah Tahap 1 selesai dan skema pesanan dari koordinator masuk ke `main`

| Modul | Pemegang | Syarat |
|---|---|---|
| Pesan layanan (`/pesan`), pesanan saya, dan detail pesanan | Pascal | T2 selesai |
| Detail pesanan admin, jadwal dan penugasan, verifikasi pembayaran | Fira | Skema pesanan |
| Keranjang dan checkout | Atta | T1 selesai |
| Panel teknisi (7 halaman) dan pendaftaran mitra | Harry | Skema pesanan. Desain teknisi belum ada (`docs/ui_teknisi` kosong), jadi tugas ini diberi label `butuh-desain` |

### Koordinator

- Skema pesanan, item pesanan, pembayaran, dan jadwal, beserta Enum status pesanan, sebelum Tahap 2 dimulai.
- Pengaturan, konten beranda, dan laporan.
- Review dan merge semua Pull Request, serta menjaga layout dan komponen bersama.

## 3. Kaitan dengan Penilaian Asistensi

Setiap modul harus memperlihatkan indikator berikut pada minggunya. Kerjakan berurutan, dan commit di tiap tahap supaya riwayatnya terlihat.

| Minggu | Yang harus ada di modulmu |
|---|---|
| 12 | Tabel dan migration, form tambah (create dan store), `__construct` (injeksi service), type-hint `Request` |
| 13 | Edit, update, dan hapus; `extends Model`; menimpa `$table`, `$primaryKey`, `$fillable`; tahu dari mana method seperti `find()` berasal |
| 14 | Validasi di Form Request; accessor atau mutator di model; `protected` atau `private` yang bisa kamu jelaskan alasannya |
| 15 | Enum atau konstanta untuk status dan kategori; pencarian atau filter; membedakan `->` dan `::` |
| 16 | Menjelaskan alur Route → Controller → Service → Model → View, dan mengubah satu method kecil secara langsung dalam 3 menit |

## 4. Isi Tugas Tahap 1

Setiap blok di bawah dipakai langsung sebagai isi Issue.

### T1: Produk dan stok (Atta)

**Tujuan.** Data produk dikelola admin dan tampil di halaman Toko, seperti modul Layanan.

**Yang dibuat**
- Model `Produk` dengan migration, factory, dan seeder (`php artisan make:model Produk -mfs`).
- Kolom awal: nama, slug, kategori (Enum `KategoriProduk`), deskripsi, harga, stok, satuan, gambar, aktif.
- `ProdukService`, `Admin\ProdukRequest`, dan `Admin\ProdukController` untuk CRUD di `/admin/produk`, termasuk unggah foto.
- Ubah `TokoController` agar membaca database (data dummy dihapus) dan buat halaman detail `/toko/{slug}`.
- Riwayat stok dan modal restok dikerjakan setelah CRUD produk selesai.

**Catatan.** Desain admin produk belum ada di `docs/ui_admin`. Ikuti gaya halaman admin Layanan. Kalau perlu desain, tulis di komentar Issue dan beri label `butuh-desain`.

### T2: Jenis AC, tarif, dan estimasi biaya (Pascal)

**Tujuan.** Tab Tarif dan Jenis AC di admin Layanan memakai data database. Pelanggan bisa menghitung estimasi biaya.

**Yang dibuat**
- Model `JenisAc` dan `TarifLayanan` (id layanan, id jenis AC, tarif yang boleh kosong sebagai tanda tidak tersedia).
- Route `admin.layanan.jenis-ac.*` dan `admin.layanan.tarif.edit` yang sudah dirujuk di `resources/views/admin/layanan/index.blade.php`.
- Hapus `jenisAcDummy()` dan `tarifDummy()` dari `Admin\LayananController` setelah datanya ada di database. Kepemilikan file itu pindah ke kamu untuk tugas ini.
- Tabel tarif per jenis AC di halaman detail layanan.
- Halaman `/estimasi`: pilih layanan, jenis AC, dan jumlah unit, lalu estimasi tampil.

### T3: Ulasan dan halaman statis (Fira)

**Tujuan.** Halaman Ulasan membaca database dan admin bisa mengatur tampilnya ulasan.

**Yang dibuat**
- Model `Ulasan` (nama pelanggan, rating 1 sampai 5, komentar, tampil).
- Hitung rata-rata dan sebaran rating di service, bukan di view.
- Ubah `UlasanController` agar membaca database, dan buat tampilan admin untuk menampilkan atau menyembunyikan ulasan.
- Halaman `/kebijakan-privasi` dan `/syarat` (isi dari PRD).
- Alur "pelanggan menulis ulasan setelah pesanan selesai" ditunda ke Tahap 2.

### T4: Akun dan pengguna (Harry)

**Tujuan.** Pelanggan mengelola profilnya. Admin mengelola daftar pelanggan dan teknisi.

**Yang dibuat**
- `/akun/profil`: ubah nama, nomor telepon, alamat, dan patokan (tabel `pelanggan` sudah ada), serta ganti kata sandi dengan `Password::min(8)`.
- Admin pelanggan: daftar dan detail (`/admin/pelanggan`).
- Admin teknisi: daftar dan persetujuan akun (`/admin/teknisi`) memakai dialog konfirmasi.
- Model `User`, `Pelanggan`, dan `Teknisi` sudah ada. Jangan memasukkan kolom `role` ke `$fillable`: itu sengaja agar peran tidak bisa diubah lewat input form.

### Selesai kalau (semua tugas)

- [ ] CRUD berjalan dari form sampai database, dan tampilan publiknya (bila ada) membaca database
- [ ] Ada tes Pest: berhasil, validasi gagal, dan data tidak ditemukan (`php artisan make:test --pest NamaTest`)
- [ ] `vendor\bin\pint --dirty --format agent` bersih
- [ ] `php artisan test --compact` lulus
- [ ] Hanya file modulmu yang berubah (`git status` dicek), tanpa `.env`
- [ ] Setiap bagian kode bisa kamu jelaskan dengan kata sendiri

## 5. Bantuan AI

AI boleh dipakai untuk belajar dan membantu menulis kode, tetapi **kamu yang harus paham dan bertanggung jawab atas kodenya**. Minggu 16 menguji "tidak terindikasi copy-paste tanpa pemahaman" dan meminta kamu mengubah kode langsung.

**Aturan**
- Jangan menempel isi `.env`, kata sandi, atau token ke AI.
- Mintalah AI menjelaskan, bukan hanya menghasilkan kode. Jangan commit kode yang belum kamu mengerti.
- Tempel file rujukan modul Layanan supaya AI meniru pola yang sama.
- Kalau memakai Claude Code di folder project, aturan project di `AGENTS.md` terbaca otomatis.

### Prompt dasar (tempel di awal sesi, ganti bagian dalam kurung siku)

```
Kamu asisten belajar Laravel untuk mahasiswa. Proyek: website CV Tiga Putra Teknik (Laravel 12, PHP 8.2+, Blade, Tailwind, MySQL, Pest).
Tugasku: modul [NAMA MODUL]. Aku mengerjakan Minggu [N] mata kuliah OOP: [konsep minggu itu].

Aturan:
1. Tiru pola modul Layanan yang sudah ada (file kutempel di bawah). Pakai gaya yang sama: nama berbahasa Indonesia, Service class dengan constructor injection, Form Request untuk validasi, Enum untuk kategori atau status, view Blade dengan komponen x-admin.*.
2. Hanya buat atau ubah file dalam daftar ini: [DAFTAR FILE MILIKKU]. Jangan ubah migration lama, .env, composer.json, atau file milik orang lain.
3. Gunakan php artisan make: untuk membuat file. Perubahan tabel selalu lewat migration baru.
4. Jangan tambah paket baru.
5. Mode belajar: jelaskan dulu rencananya, tunggu aku setuju, lalu kerjakan SATU file per langkah. Setiap file jelaskan dengan bahasa sederhana apa fungsi tiap bagian dan kaitannya dengan konsep OOP minggu ini (class, object, property, method, constructor, extends, visibility, static).
6. Setelah selesai, tulis tes Pest untuk: berhasil, validasi gagal, dan data tidak ditemukan.
7. Aku akan diminta dosen menjelaskan dan mengubah kode ini langsung, jadi jangan buat kode yang terlalu rumit.

File referensi: [tempel Layanan.php, LayananService.php, LayananRequest.php, Admin/LayananController.php, admin/layanan/_form.blade.php]
```

### Tambahan per tugas (letakkan di bawah prompt dasar)

- **T1:** "Buat modul Produk: tabel `produk` (nama, slug, kategori Enum, deskripsi, harga, stok, satuan, gambar, aktif), CRUD admin di /admin/produk dengan unggah foto, lalu ubah TokoController agar membaca dari database."
- **T2:** "Buat model JenisAc dan TarifLayanan (tarif boleh kosong berarti tidak tersedia). Sambungkan ke route admin.layanan.jenis-ac.* dan admin.layanan.tarif.edit yang sudah dirujuk di view index. Setelah itu buat halaman /estimasi."
- **T3:** "Buat modul Ulasan dengan rating 1 sampai 5. Hitung rata-rata dan sebaran rating di service. Admin bisa menyembunyikan ulasan. Lalu buat halaman /kebijakan-privasi dan /syarat."
- **T4:** "Buat halaman profil pelanggan (edit data, alamat, ganti kata sandi dengan Password::min(8)) dan halaman admin untuk daftar pelanggan serta persetujuan teknisi."

### Prompt pendukung

**Sebelum membuka PR**
```
Review perubahan di bawah ini seperti dosen penguji. Cari: bug, kode yang sulit kujelaskan, yang melanggar pola modul Layanan, dan tes yang kurang. Jangan perbaiki dulu, sebutkan temuannya.
[tempel hasil: git diff main]
```

**Saat ada error**
```
Aku menjalankan [perintah] dan muncul error ini: [tempel]. Kode terkait: [tempel]. Jelaskan penyebabnya dulu, baru beri perbaikannya.
```

**Saat tidak paham suatu kode**
```
Jelaskan kode ini baris per baris. Tunjukkan mana yang property, mana yang method, mana yang constructor, dan dari class mana method bawaannya diwarisi. Beri satu pertanyaan latihan yang mungkin ditanyakan dosen.
[tempel kode]
```

## 6. Membuat Issue Tugas

Koordinator membuat satu Issue per tugas, dengan isi dari blok di bagian 4. Issue lama (Toko, Layanan, Ulasan, dan Tentang dan Kontak) ditutup karena halamannya sudah ada di `main`. Langkah teknisnya ada di Kontribusi.md bagian 8.7.
