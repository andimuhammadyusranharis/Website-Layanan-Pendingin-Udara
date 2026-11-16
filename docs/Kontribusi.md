# Panduan Kerja Tim

Perintah ditulis untuk terminal di Windows (PowerShell atau terminal bawaan VS Code). Bagian 1 sampai 7 untuk semua anggota. Bagian 8 khusus koordinator. Pembagian tugas dan prompt bantuan AI ada di [Pembagian_Tugas.md](Pembagian_Tugas.md).

## 1. Pembagian Peran

| Peran | Siapa | Tugas |
|---|---|---|
| Koordinator | Yusran | Manajemen proyek dan progres, desain Figma, arsitektur dan skema inti (pesanan, pembayaran, jadwal), layout dan komponen bersama, review dan merge semua PR |
| Anggota | Atta, Pascal, Fira, Harry | Memegang satu modul penuh: tabel database, model, service, validasi, controller, tampilan, dan tes. Pembagian modul ada di [Pembagian_Tugas.md](Pembagian_Tugas.md) |

Setiap anggota memegang modul sendiri supaya kode buatannya terlihat jelas di riwayat Git. Itu juga yang diminta penilaian asistensi: tiap mahasiswa menunjukkan kode OOP buatannya sendiri dan menjelaskannya langsung. Pembagian per modul juga membuat setiap orang bekerja di file yang berbeda, jadi konflik tetap sedikit.

**Boleh dikerjakan anggota (untuk modul yang ditugaskan)**
- File modulmu di `app/` (model, service, request, controller, enum).
- Migration baru, factory, dan seeder untuk tabel modulmu.
- Route modulmu di `routes/web.php`, dalam blok terpisah dengan komentar nama modul.
- File Blade, gaya, dan JavaScript untuk halaman modulmu, di `resources/`.
- Gambar halamanmu di `public/images/`.
- Tes modulmu di `tests/`.

**Tidak boleh diubah (minta lewat Issue atau grup)**
- Migration atau seeder yang sudah ada di `main`, dan tabel milik modul orang lain.
- `config/`, `bootstrap/`, `.env.example`, `.github/`.
- Layout dan komponen bersama (`resources/views/components/layouts/` dan komponen yang dipakai banyak halaman). Butuh komponen baru yang dipakai banyak halaman? Buka Issue.
- `composer.json`, `composer.lock`, `package.json`, `package-lock.json`, `vite.config.js`. Menambah paket harus disetujui koordinator.
- File di modul orang lain.

Tugas yang murni tampilan (label `halaman`) tetap hanya menyentuh file Blade, gaya, dan JavaScript halaman itu.

## 2. Alur Besar

```
Koordinator: buat Issue per tugas (isi: tujuan, yang dibuat, selesai kalau)
   ↓
Anggota: ambil Issue, buat branch, kerjakan modul dari migration sampai tampilan
   ↓
Pull Request → koordinator review → merge
   ↓
Anggota berikutnya mengambil update dan melanjutkan modul yang bergantung padanya
```

### Isi Issue

Setiap Issue modul memuat:
- tujuan modul,
- daftar yang dibuat (tabel, model, service, halaman, route),
- file rujukan (modul Layanan sebagai contoh pola),
- daftar "Selesai kalau".

Setiap Issue halaman (label `halaman`) memuat kontrak: apa yang diterima halaman dari backend. Isinya:
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

Aturan untuk semua halaman: tampilkan data dengan memakai variabel dari controller (`@foreach ($layanan as $item)`), jangan menulis teks atau harga langsung di Blade. Dengan begitu, mengganti sumber data tidak mengubah file Blade.

Kalau Issue kurang jelas atau perlu data tambahan, tulis di komentar Issue. Jangan menebak lalu membuat sendiri.

## 3. Pengaturan Awal (sekali saja)

Atur identitas Git. Pakai email yang sama dengan akun GitHub supaya commit tercatat atas namamu:

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

Akun admin uji dari seeder: `admin@tigaputrateknik.test`, kata sandi `password`. Akun pelanggan dibuat lewat halaman `/daftar`.

**Setelah setiap `git pull`.** Kalau yang masuk berisi migration, seeder, atau paket baru, jalankan:

```bash
composer install
php artisan migrate
php artisan db:seed --class=NamaSeeder   # seeder modul yang baru masuk
npm install
```

`php artisan migrate --seed` aman dijalankan ulang. Foto yang diunggah lewat admin tersimpan di `storage/app/public` dan **tidak ikut Git**, jadi di komputermu foto tampil "Foto menyusul" sampai kamu mengunggahnya sendiri.

**Soal database.** Tabel dibuat dan diubah hanya lewat migration, tidak lewat DBeaver. Kamu membuat migration dan seeder untuk modulmu sendiri. Perubahan skema di modul orang lain diminta lewat Issue. Kalau database lokalmu berantakan, `php artisan migrate:fresh --seed` mereset semuanya dari nol (semua data lokal terhapus, dan itu tidak apa-apa karena hanya data contoh).

`.env` hanya milik komputermu. Jangan dikirim lewat GitHub atau grup.

## 4. Alur Harian

Alur ini berlaku untuk semua anggota. **Jangan pernah bekerja langsung di `main`.**

**1. Mulai dari `main` terbaru**

```bash
git checkout main
git pull origin main
```

Lalu jalankan perintah "Setelah setiap `git pull`" di bagian 3 kalau perlu.

**2. Buat branch untuk tugasmu**

Format: `fitur/nama-modul` untuk modul, `halaman/nama-halaman` untuk tugas halaman murni, `fix/nama-masalah` untuk perbaikan bug. Huruf kecil, tanda hubung, tanpa spasi.

```bash
git checkout -b fitur/produk
```

Satu branch untuk satu tugas. Tugas besar dipecah menjadi beberapa PR kecil (misalnya CRUD dulu, lalu riwayat stok).

**3. Kerjakan, cek rutin**

```bash
git status
git diff
```

Pastikan hanya file modulmu yang berubah. Kalau ada file lain yang muncul dan kamu tidak mengubahnya dengan sengaja, tanyakan sebelum commit.

**4. Commit kecil dan sering**

```bash
git add .
git commit -m "fitur(produk): tambah model dan migration produk"
```

Format pesan: `tipe(scope): ringkasan singkat dalam bentuk perintah`.

| Tipe | Untuk |
|---|---|
| `fitur` | menambah kemampuan modul (model, service, controller, tampilan) |
| `halaman` | membangun atau mengubah halaman saja |
| `fix` | memperbaiki bug |
| `test` | menambah atau memperbaiki tes |
| `chore` | pembersihan atau perawatan tanpa mengubah perilaku |
| `docs` | dokumentasi |

Contoh: `fitur(produk): tambah CRUD admin dengan unggah foto`, `test(produk): tes validasi harga negatif`, `fix(layanan): perbaiki kartu yang terpotong di ponsel`.

**5. Cek sebelum push**

```bash
php artisan test --compact
vendor\bin\pint --dirty --format agent
```

Keduanya harus bersih. Lalu ambil update `main`:

```bash
git fetch origin
git merge origin/main
```

**6. Push**

Pertama kali untuk branch ini:

```bash
git push -u origin fitur/produk
```

Berikutnya cukup `git push`.

**7. Buka Pull Request**

Di GitHub biasanya muncul tombol **Compare & pull request** setelah push. Kalau tidak: *Pull requests* → *New pull request*. Atur base: `main`, compare: branch kamu. Isi template (muncul otomatis):
- `Closes #nomor-issue`,
- ringkasan "Yang dikerjakan",
- screenshot desktop (1280 px) dan ponsel (360 px) untuk perubahan tampilan.

Pada daftar cek di template, centang yang relevan dengan tugasmu. Butir tentang frame Figma dan "hanya file halaman saya yang berubah" hanya berlaku untuk tugas halaman murni. Untuk modul, tuliskan tambahan di deskripsi: tabel apa yang dibuat, dan perintah yang harus dijalankan anggota lain setelah merge (misalnya `php artisan migrate`).

Klik **Create pull request**. Kamu tidak merge PR sendiri.

**8. Tanggapi review**

Kalau ada komentar dari koordinator, perbaiki di branch yang sama, commit, lalu `git push`. PR ter-update otomatis.

**9. Setelah PR di-merge**

```bash
git checkout main
git pull origin main
git branch -d fitur/produk
```

## 5. Standar Tampilan

Ini yang akan dicek saat review.

- Sesuai frame Figma. Kalau desain tidak jelas atau belum ada, tanya. Jangan berimprovisasi.
- Pakai layout dan komponen yang sudah ada, dengan cara styling yang sama seperti halaman lain. Komponen yang hanya dipakai halamanmu disimpan di dalam folder halamanmu.
- Responsif. Dirancang dari layar ponsel dulu (mobile-first). Cek di lebar sekitar 360 px, 768 px, dan 1280 px.
- Teks isi minimal 16 px dan area sentuh minimal 44 px. Teks kontras cukup. Status tidak hanya dibedakan lewat warna, sertakan teks.
- Setiap halaman punya kondisi kosong, loading, sukses, dan galat bila relevan.
- Bahasa Indonesia sehari-hari, singkat, tanpa istilah teknis.
- Form: sertakan `@csrf`, tampilkan galat validasi dari server dan isi lama (`$errors`, `old()`). Validasi di JavaScript hanya tambahan untuk kenyamanan, bukan pengganti validasi server.
- Gambar: simpan di `public/images/`, kompres, nama huruf kecil dengan awalan nama halaman (`layanan-hero.jpg`). Target halaman termuat di bawah 3 detik.
- Aksi yang tidak bisa dibatalkan memakai dialog konfirmasi (`x-admin.tombol-aksi` dengan `:konfirmasi`), dan hasil simpan, ubah, atau hapus ditampilkan lewat notifikasi (`->with('status', '...')`).

### 5.1 Standar Kode (modul backend)

- Ikuti pola modul Layanan: migration → model → service → Form Request → controller → view → tes.
- Nama class, method, dan variabel memakai bahasa Indonesia yang deskriptif, sama seperti kode yang ada.
- Controller tipis: menerima request, memanggil service, mengembalikan view atau redirect. Logika dan query ada di service.
- Validasi di Form Request, dengan pesan galat berbahasa Indonesia.
- Kategori dan status memakai Enum atau konstanta, bukan teks yang ditulis berulang.
- Perubahan tabel selalu migration baru. Migration yang sudah ada di `main` tidak diedit.
- Setiap modul punya tes Pest: berhasil, validasi gagal, dan data tidak ditemukan.
- Jalankan `vendor\bin\pint --dirty --format agent` sebelum commit.
- Kode yang kamu kumpulkan harus bisa kamu jelaskan sendiri. Bantuan AI boleh, panduannya di [Pembagian_Tugas.md](Pembagian_Tugas.md) bagian 5.

## 6. Konflik dan Salah Langkah

Karena setiap orang mengerjakan modul berbeda, konflik seharusnya jarang. Titik rawannya `routes/web.php` dan `database/seeders/DatabaseSeeder.php`. Tambahkan perubahanmu sebagai blok terpisah dengan komentar nama modul, dan jangan merapikan baris orang lain.

Kalau muncul `CONFLICT` saat `git merge origin/main`:

- Konflik di file modulmu sendiri: buka file, cari `<<<<<<<`, `=======`, `>>>>>>>`, tentukan isi akhir, hapus penandanya, lalu `git add nama-file` dan `git commit`.
- Konflik di file yang bukan milikmu: **berhenti**, jalankan `git merge --abort`, lalu kabari koordinator.

**Terlanjur mengedit di `main` (belum commit).** Buat branch baru, perubahannya ikut terbawa:

```bash
git checkout -b fitur/nama-modul
```

**Terlanjur commit di `main` lokal (belum push).** Pastikan `git status` bersih, lalu:

```bash
git branch fitur/nama-modul
git reset --hard origin/main
git checkout fitur/nama-modul
```

**Push ditolak (`rejected`).**

```bash
git pull origin nama-branch-kamu
git push
```

Push langsung ke `main` memang ditolak GitHub, jadi `main` aman. Kalau muncul pesan itu, buat branch dan PR seperti biasa.

Ragu dengan suatu perintah? Tanya di grup sebelum menjalankannya.

## 7. Dilarang

- Push langsung ke `main`, atau merge PR sendiri.
- `git push --force`.
- Meng-commit `.env`, `vendor/`, `node_modules/`, atau data pelanggan asli.
- Mengubah file di luar modulmu tanpa izin koordinator.
- Mengedit migration atau seeder yang sudah ada di `main`.
- Mengubah struktur database lewat DBeaver.
- Menjalankan `git reset --hard` tanpa paham dampaknya.
- Menempel `.env`, kata sandi, atau token ke AI.
- Mengumpulkan kode hasil AI yang tidak kamu pahami.

---

## 8. Untuk Koordinator

### 8.1 Urutan menyiapkan tugas

1. **Fondasi sudah di `main`:** layout, autentikasi, gaya, dan modul Layanan (rujukan pola) sudah ada. Anggota bercabang dari `main` terbaru.
2. **Sebelum merge, periksa isi PR:** `.env`, `vendor/`, dan `node_modules/` tidak boleh ikut. Jalankan `git ls-files .env`: kalau ada output, file itu ter-track dan harus dikeluarkan dengan `git rm --cached .env`.
3. **Tentukan layout dan komponen bersama** sebelum halaman lain dibuat (navbar, footer, tombol, kartu, form, badge status, dialog, notifikasi), sesuai token di PRD bagian 6.4.
4. **Untuk tiap tugas:** buat Issue dengan isi dari [Pembagian_Tugas.md](Pembagian_Tugas.md) bagian 4, beri label dan milestone, lalu assign.
5. **Tahap 2 baru dimulai setelah skema pesanan siap.** Buat migration pesanan, item pesanan, pembayaran, dan jadwal, beserta Enum status pesanan, lalu merge ke `main` sebelum tugas yang bergantung padanya di-assign.
6. **Branch `Branch-Yusran` dan branch `halaman/*` lama:** cek isinya, lalu hapus yang sudah ter-merge (`git push origin --delete nama-branch`).

### 8.2 Papan proyek dan pemantauan progres

Pakai GitHub Issues dan GitHub Projects (papan Kanban) dengan kolom:

`Backlog` → `Siap dikerjakan` → `Dikerjakan` → `Review` → `Selesai`

Sebuah Issue masuk `Siap dikerjakan` kalau tugasnya jelas dan tidak menunggu tugas lain. Dengan aturan ini anggota tidak pernah menunggu.

Label yang sudah ada di repo:

| Kelompok | Label |
|---|---|
| Area | `pelanggan`, `admin`, `teknisi` |
| Jenis | `halaman`, `backend`, `bug` |
| Prioritas | `P1`, `P2`, `P3` |
| Status | `butuh-desain`, `blocked` |

Gunakan milestone `P1`, `P2`, `P3`. GitHub otomatis menampilkan persentase Issue yang selesai per milestone. Untuk melihat beban tiap orang, kelompokkan papan berdasarkan assignee.

### 8.3 Review dan merge

Cek tiap PR saat masuk, jangan menunggu menumpuk:
- sesuai Figma (bila ada tampilan) dan responsif,
- mengikuti pola modul Layanan dan standar kode bagian 5.1,
- tes lulus dan ada tes baru untuk modulnya,
- tidak ada perubahan di luar modul, dan tidak ada `.env` atau data pribadi,
- penulis bisa menjelaskan kodenya (tanyakan satu atau dua hal di komentar PR).

Merge dengan **Squash and merge**, lalu hapus branch. Konflik di file bersama diselesaikan oleh koordinator.

Karena Squash and merge menjadikan satu commit di `main`, riwayat commit atas nama tiap anggota tetap terlihat di PR dan di branch sebelum dihapus. Kalau penilaian meminta bukti commit per orang, tampilkan lewat halaman PR atau `git log --author`.

### 8.4 Database

Anggota boleh membuat migration baru untuk tabel modulnya. Koordinator memeriksa:
- satu perubahan skema, satu migration baru. Migration yang sudah di `main` tidak diedit.
- urutan dan relasi: tabel yang dirujuk foreign key harus sudah ada di `main` lebih dulu.
- data inti di seeder dibuat tetap (bukan acak) supaya semua orang melihat hal yang sama. Seeder yang dijalankan ulang tidak boleh error (pakai `updateOrCreate` atau cek keberadaan data). Akun uji dicatat di README dan bagian 3.
- sebelum PR berisi skema baru di-merge, buktikan bisa direproduksi: buat database kosong, jalankan `php artisan migrate:fresh --seed`, lalu pastikan hasilnya sama dengan databasemu. Apa pun yang hanya ada di databasemu dan tidak punya migration atau seeder akan hilang di komputer anggota lain.
- `phpunit.xml` memakai SQLite di memori, terpisah dari database kerja, jadi `php artisan test` tidak mengosongkan data.
- perubahan database dikabarkan ke grup dengan perintah yang harus dijalankan setelah `git pull`.

### 8.5 Menyambungkan data asli

Untuk halaman yang masih berdata dummy, tugas modul yang bersangkutan menggantinya dengan Eloquent dan Service **tanpa mengubah nama variabel** yang dikirim ke view, kecuali Issue menyebutkan sebaliknya. Kalau bentuk data harus berubah, tulis di Issue, jangan mengubah Blade orang lain diam-diam.

### 8.6 Pengaturan repository

- Semua anggota sudah collaborator dengan akses Write.
- Branch `main` sudah dilindungi ruleset GitHub bernama `main`: tidak bisa dihapus, tidak bisa force-push, dan perubahan wajib lewat Pull Request. Peran admin (koordinator) dapat melewati aturan itu. Biasakan tetap lewat PR supaya riwayat dan review tercatat.
- Cek di *Settings → General → Pull Requests*: aktifkan squash merging dan *Automatically delete head branches* (saat ini hapus otomatis belum aktif, jadi branch dihapus manual setelah merge).
- Penulis PR tidak bisa meng-approve PR-nya sendiri di GitHub. PR milik koordinator di-merge dengan bypass admin atau di-approve anggota lain.
- Beri anggota akses lihat ke file Figma lewat pengaturan berbagi Figma, dan cek apakah akses itu cukup untuk melihat ukuran, warna, dan font di frame.

### 8.7 Membuat Issue tugas dan menutup Issue lama

**A. Tutup Issue lama (#4, #5, #6, #7).** Halamannya sudah ada di `main`, dan pekerjaan lanjutannya dicakup Issue modul baru. Di GitHub: buka Issue → tombol **Close issue** → pilih **Close as completed**, atau tulis komentar sambil menutup. Lewat terminal:

```bash
gh issue close 4 --reason completed --comment "Halaman Toko sudah di main (PR #9). Lanjutan: Issue modul Produk dan stok."
gh issue close 5 --reason completed --comment "Halaman Layanan sudah di main (PR #10 dan #13). Lanjutan: Issue modul Jenis AC dan tarif."
gh issue close 6 --reason completed --comment "Tampilan Ulasan sudah di main (PR #10). Lanjutan: Issue modul Ulasan."
gh issue close 7 --reason completed --comment "Tampilan Tentang dan Kontak sudah di main (PR #10)."
```

**B. Buat Issue modul.** Salin isi tiap blok T1 sampai T4 dari [Pembagian_Tugas.md](Pembagian_Tugas.md) bagian 4.

Lewat web: *Issues* → *New issue* → *Get started* pada template **Halaman**, atau buka *Open a blank issue*. Isi judul dan isi, lalu di panel kanan atur *Assignees*, *Labels*, dan *Milestone*. Klik **Submit new issue**.

Lewat terminal (simpan isi blok ke file teks sementara, misalnya `t1.md`, lalu):

```bash
gh issue create --title "Modul: Produk dan stok (T1)" --body-file t1.md --assignee bleme31 --label backend,admin,pelanggan,P1 --milestone P1
gh issue create --title "Modul: Jenis AC, tarif, dan estimasi (T2)" --body-file t2.md --assignee PascalAncelo --label backend,admin,pelanggan,P1 --milestone P1
gh issue create --title "Modul: Ulasan dan halaman statis (T3)" --body-file t3.md --assignee firayanti --label backend,admin,pelanggan,P2 --milestone P2
gh issue create --title "Modul: Akun dan pengguna (T4)" --body-file t4.md --assignee Alam2250 --label backend,admin,pelanggan,P1 --milestone P1
```

**C. Setelah Issue dibuat:** kabari grup agar tiap anggota membaca Issue-nya, Pembagian_Tugas.md, dan Kontribusi.md, lalu mulai dari bagian 4 (Alur Harian).
