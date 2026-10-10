# Panduan Kerja Tim

Perintah ditulis untuk terminal di Windows (PowerShell atau terminal bawaan VS Code). Bagian 1 sampai 7 untuk semua anggota. Bagian 8 khusus koordinator.

## 1. Pembagian Peran

| Peran | Siapa | Tugas |
|---|---|---|
| Koordinator | Yusran | Manajemen proyek dan progres, desain Figma, backend (route, controller, model, logika bisnis), database (migration dan seeder), layout dan komponen bersama, review dan merge semua PR |
| Pembuat halaman | Attahilla, Harry, Adniel, Firayanti | Membangun halaman sesuai Figma dan kontrak di Issue |

Pembuat halaman mengerjakan tampilan: file Blade, gaya, dan JavaScript sisi klien untuk halaman yang ditugaskan. Pembagian ini dibuat supaya setiap orang bekerja di file yang berbeda dan konflik tetap sedikit.

**Boleh diubah oleh pembuat halaman**
- File halaman yang ditugaskan di `resources/views/`.
- Gaya dan JavaScript khusus halamannya.
- Gambar yang dipakai halamannya, di `public/images/`.

**Tidak boleh diubah (minta lewat Issue atau grup)**
- `routes/`, `app/` (controller, model, service), `database/`, `config/`.
- Layout dan komponen bersama.
- `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`.
- `.env.example`, `.github/`.

## 2. Alur Besar

```
Koordinator: buat Issue + siapkan route dan data dummy di main
   ↓
Pembuat halaman: ambil Issue, buat branch, bangun halaman
   ↓
Pull Request → koordinator review → merge
   ↓
Koordinator: sambungkan data asli (tampilan tidak berubah)
```

### Kontrak halaman

Setiap Issue halaman memuat kontrak: apa yang diterima halaman dari backend. Isinya:
- tautan frame Figma,
- lokasi file Blade,
- path dan nama route,
- variabel yang dikirim ke view, beserta contoh bentuk datanya,
- untuk form: method, action, dan nama setiap field,
- interaksi JavaScript yang dibutuhkan.

Contoh variabel untuk halaman Layanan:

```php
$layanan = [
    ['id' => 1, 'nama' => 'Cuci AC', 'harga_mulai' => 75000],
    ['id' => 2, 'nama' => 'Isi Freon', 'harga_mulai' => 150000],
];
```

Aturan untuk pembuat halaman: tampilkan data dengan memakai variabel itu (`@foreach ($layanan as $item)`), jangan menulis teks atau harga langsung di Blade. Dengan begitu, saat koordinator mengganti data dummy dengan data database, file Blade tidak perlu diubah.

Kalau kontrak kurang jelas atau perlu data tambahan, tulis di komentar Issue. Jangan menebak lalu membuat sendiri.

## 3. Pengaturan Awal (sekali saja)

Atur identitas Git. Pakai email yang sama dengan akun GitHub:

```bash
git config --global user.name "Nama Kamu"
git config --global user.email "email-akun-github@contoh.com"
```

Clone dan siapkan proyek:

```bash
cd D:\Herd
git clone https://github.com/andimuhammadyusranharis/Website-Layanan-Pendingin-Udara.git cv_tigaputrateknik
cd cv_tigaputrateknik

composer install
copy .env.example .env
php artisan key:generate
```

Buat database kosong `cv_tigaputrateknik` di DBeaver, isi `DB_*` di `.env`, lalu:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run dev
```

**Soal database untuk pembuat halaman.** Kamu tetap butuh database lokal supaya aplikasi bisa jalan (login dan sesi bergantung padanya), tapi kamu tidak mengubah isinya. Struktur tabel dan data contoh sepenuhnya dikelola koordinator lewat migration dan seeder. Tugasmu hanya menjalankan perintah ini setelah `git pull` yang membawa perubahan database:

```bash
php artisan migrate --seed
```

Kalau database lokalmu berantakan, `php artisan migrate:fresh --seed` mereset semuanya dari nol (semua data lokal terhapus, dan itu tidak apa-apa karena hanya data contoh).

`.env` hanya milik komputermu. Jangan dikirim lewat GitHub atau grup.

## 4. Alur Harian Pembuat Halaman

**1. Mulai dari `main` terbaru**

```bash
git checkout main
git pull origin main
```

Lalu jalankan `composer install`, `php artisan migrate --seed`, dan `npm install` kalau pull membawa perubahan di bagian itu.

**2. Buat branch untuk halamanmu**

Format: `halaman/nama-halaman`. Huruf kecil, tanda hubung, tanpa spasi.

```bash
git checkout -b halaman/layanan-daftar
```

Contoh lain: `halaman/katalog-toko`, `halaman/detail-produk`. Untuk perbaikan bug: `fix/nama-masalah`.

**3. Bangun halaman, cek rutin**

```bash
git status
git diff
```

Pastikan hanya file halamanmu yang berubah. Kalau ada file lain yang muncul dan kamu tidak mengubahnya dengan sengaja, tanyakan sebelum commit.

**4. Commit kecil dan sering**

```bash
git add .
git commit -m "halaman(layanan): tambah daftar layanan"
```

Format pesan: `tipe(scope): ringkasan singkat dalam bentuk perintah`.

| Tipe | Untuk |
|---|---|
| `halaman` | membangun atau mengubah halaman |
| `fix` | memperbaiki bug tampilan atau interaksi |
| `docs` | dokumentasi |

Contoh: `halaman(toko): tambah filter dan urut harga`, `fix(layanan): perbaiki kartu yang terpotong di ponsel`.

**5. Ambil update `main` sebelum push**

```bash
git checkout main
git pull origin main
git checkout halaman/layanan-daftar
git merge main
```

**6. Push**

Pertama kali untuk branch ini:

```bash
git push -u origin halaman/layanan-daftar
```

Berikutnya cukup `git push`.

**7. Buka Pull Request**

Di GitHub: *Pull requests* → *New pull request* → base: `main`, compare: branch kamu → isi deskripsi (template muncul otomatis) → *Create pull request*. Sertakan `Closes #nomor-issue` dan screenshot tampilan desktop dan ponsel.

**8. Tanggapi review**

Kalau ada komentar dari koordinator, perbaiki di branch yang sama, commit, lalu `git push`. PR ter-update otomatis. Kamu tidak merge PR sendiri.

**9. Setelah PR di-merge**

```bash
git checkout main
git pull origin main
git branch -d halaman/layanan-daftar
```

## 5. Standar Tampilan

Ini yang akan dicek saat review.

- Sesuai frame Figma. Kalau desain tidak jelas atau belum ada, tanya. Jangan berimprovisasi.
- Pakai layout dan komponen yang sudah ada, dengan cara styling yang sama seperti halaman login dan registrasi. Butuh komponen yang akan dipakai halaman lain? Buka Issue. Komponen yang hanya dipakai halamanmu disimpan di dalam folder halamanmu.
- Responsif. Dirancang dari layar ponsel dulu (mobile-first). Cek di lebar sekitar 360 px, 768 px, dan 1280 px.
- Teks isi minimal 16 px dan area sentuh minimal 44 px. Teks kontras cukup. Status tidak hanya dibedakan lewat warna, sertakan teks.
- Setiap halaman punya kondisi kosong, loading, sukses, dan galat bila relevan.
- Bahasa Indonesia sehari-hari, singkat, tanpa istilah teknis.
- Form: sertakan `@csrf`, tampilkan galat validasi dari server dan isi lama (`$errors`, `old()`). Validasi di JavaScript hanya tambahan untuk kenyamanan, bukan pengganti validasi server.
- Gambar: simpan di `public/images/`, kompres, nama huruf kecil dengan awalan nama halaman (`layanan-hero.jpg`). Target halaman termuat di bawah 3 detik.

## 6. Konflik dan Salah Langkah

Karena setiap orang mengerjakan file berbeda, konflik seharusnya jarang. Kalau muncul `CONFLICT` saat `git merge main`:

- Konflik di file halamanmu sendiri: buka file, cari `<<<<<<<`, `=======`, `>>>>>>>`, tentukan isi akhir, hapus penandanya, lalu `git add nama-file` dan `git commit`.
- Konflik di file yang bukan milikmu: **berhenti**, jalankan `git merge --abort`, lalu kabari koordinator.

**Terlanjur mengedit di `main` (belum commit).** Buat branch baru, perubahannya ikut terbawa:

```bash
git checkout -b halaman/nama-halaman
```

**Terlanjur commit di `main` lokal (belum push).** Pastikan `git status` bersih, lalu:

```bash
git branch halaman/nama-halaman
git reset --hard origin/main
git checkout halaman/nama-halaman
```

**Push ditolak (`rejected`).**

```bash
git pull origin nama-branch-kamu
git push
```

Ragu dengan suatu perintah? Tanya di grup sebelum menjalankannya.

## 7. Dilarang

- Push langsung ke `main`, atau merge PR sendiri.
- `git push --force`.
- Meng-commit `.env`, `vendor/`, `node_modules/`, atau data pelanggan asli.
- Mengubah file di luar wilayahmu tanpa izin koordinator.
- Mengubah struktur database lewat DBeaver.
- Menjalankan `git reset --hard` tanpa paham dampaknya.

---

## 8. Untuk Koordinator

### 8.1 Urutan menyiapkan tugas

1. **Masukkan fondasi ke `main` dulu.** Registrasi dan login yang sudah jadi di branch pribadimu dibuka sebagai PR ke `main`, lalu di-merge. Pembuat halaman harus bercabang dari fondasi yang sama (layout, autentikasi, gaya), jadi jangan menugaskan siapa pun sebelum ini selesai.
2. **Sebelum merge, periksa isi PR:** `.env`, `vendor/`, dan `node_modules/` tidak boleh ikut. Jalankan `git ls-files .env`: kalau ada output, file itu ter-track dan harus dikeluarkan dengan `git rm --cached .env`.
3. **Tentukan layout dan komponen bersama** sebelum halaman lain dibuat (navbar, footer, tombol, kartu, form, badge status), sesuai token di PRD bagian 6.4.
4. **Untuk tiap halaman:** buat route dan controller yang mengirim data dummy ke view, push ke `main`, lalu buat Issue memakai template Halaman. Baru setelah itu di-assign.
5. **Branch `Branch-Harry`:** cek isinya dulu. Kalau berisi pekerjaan yang layak dipakai, minta Harry membuka PR ke `main`, baru branch dihapus. Jangan hapus sebelum itu.

### 8.2 Papan proyek dan pemantauan progres

Pakai GitHub Issues dan GitHub Projects (papan Kanban) dengan kolom:

`Backlog` → `Siap dikerjakan` → `Dikerjakan` → `Review` → `Selesai`

Sebuah Issue baru masuk `Siap dikerjakan` kalau route dan data dummynya sudah ada dan Figma-nya sudah jadi. Dengan aturan ini pembuat halaman tidak pernah menunggu backend.

Label yang disarankan:

| Kelompok | Label |
|---|---|
| Area | `pelanggan`, `admin`, `teknisi` |
| Jenis | `halaman`, `backend`, `bug` |
| Prioritas | `P1`, `P2`, `P3` |
| Status | `butuh-desain`, `blocked` |

Gunakan milestone per prioritas (`P1`, `P2`, `P3`). GitHub otomatis menampilkan persentase Issue yang selesai per milestone. Untuk melihat beban tiap orang, kelompokkan papan berdasarkan assignee.

### 8.3 Review dan merge

Cek tiap PR saat masuk, jangan menunggu menumpuk: sesuai Figma, responsif, memakai variabel sesuai kontrak, tidak ada perubahan di luar wilayah, tidak ada `.env` atau data pribadi. Merge dengan **Squash and merge**, lalu hapus branch. Konflik di file bersama diselesaikan oleh koordinator.

### 8.4 Database

Migration dan seeder hanya kamu yang mengubah.
- Satu perubahan skema, satu migration baru. Migration yang sudah di `main` tidak diedit.
- Data inti di seeder dibuat tetap (bukan acak) supaya semua orang melihat hal yang sama: akun admin, teknisi, dan pelanggan uji, jenis AC, layanan, dan tarif. Catat akun uji di README.
- Sebelum PR berisi skema baru di-merge, buktikan bisa direproduksi: buat database kosong lain, jalankan `php artisan migrate:fresh --seed`, lalu pastikan hasilnya sama dengan databasemu. Apa pun yang hanya ada di databasemu dan tidak punya migration atau seeder akan hilang di komputer anggota lain.
- Pastikan `phpunit.xml` memakai database terpisah dari database kerjamu, supaya `php artisan test` tidak mengosongkan data.
- Perubahan database dikabarkan ke grup supaya anggota menjalankan `php artisan migrate --seed`.

### 8.5 Menyambungkan data asli

Ganti data dummy di controller dengan Eloquent atau Service **tanpa mengubah nama variabel** yang dijanjikan di kontrak. Kalau bentuk data harus berubah, buka Issue baru ke pembuat halaman, jangan mengedit Blade mereka diam-diam.

### 8.6 Pengaturan repository

- Tambahkan semua anggota sebagai collaborator dengan akses Write.
- *Settings → Branches*: aturan untuk `main`: wajib PR, minimal 1 approval, hapus approval lama saat ada commit baru, komentar harus selesai sebelum merge.
- *Settings → General → Pull Requests*: aktifkan squash merging dan *Automatically delete head branches*.
- Penulis PR tidak bisa meng-approve PR-nya sendiri di GitHub. Jadi PR milikmu butuh approval dari anggota lain, atau diatur agar admin repo boleh melewati aturan. Cek opsi bypass di pengaturan branch protection.
- Ketersediaan branch protection untuk repo private di paket gratis perlu dicek di pengaturan GitHub. Kalau tidak tersedia, aturan "tidak push ke `main`" menjadi kesepakatan tim saja.
- Beri anggota akses lihat ke file Figma lewat pengaturan berbagi Figma, dan cek apakah akses itu cukup untuk melihat ukuran, warna, dan font di frame.
