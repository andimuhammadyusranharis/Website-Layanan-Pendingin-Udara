# CV. Tiga Putra Teknik: Website Layanan dan Toko AC

Website untuk usaha service AC dan toko komponen pendingin di Pangkep. Pelanggan bisa memesan jasa dan membeli komponen secara online, sementara pemilik dan teknisi mengelola jadwal, stok, pembayaran, dan laporan dari satu sistem. Project ini dikerjakan sebagai tugas kuliah.

Rincian kebutuhan ada di [PRD](docs/PRD_Website_CV__Tiga_Putra_Teknik.md). Desain antarmuka ada di [Figma](https://www.figma.com/design/BIe8tA7KuoMlrMoitnLT2F/Web-UMKM-AC).

## Fitur Utama

- **Pelanggan:** melihat layanan dan produk, menghitung estimasi biaya, memesan jasa atau membeli komponen, memantau status, memberi ulasan.
- **Admin (pemilik):** mengelola pesanan, jadwal, stok, pembayaran, konten beranda, dan laporan.
- **Teknisi:** melihat jadwal dan detail pekerjaan, memperbarui status, mencatat pembayaran tunai.

## Tech Stack

| Bagian | Teknologi |
|---|---|
| Framework | Laravel |
| Server lokal | Laravel Herd |
| Database | MySQL, dikelola dengan DBeaver |
| Frontend | Blade dan Vite |
| Desain UI/UX | Figma |
| Version control | Git dan GitHub |

## Menjalankan di Komputer Sendiri

Prasyarat: Herd, MySQL yang aktif, Node.js, Git, dan DBeaver.

```bash
cd D:\Herd
git clone https://github.com/andimuhammadyusranharis/Website-Layanan-Pendingin-Udara.git cv_tigaputrateknik
cd cv_tigaputrateknik

composer install
copy .env.example .env
php artisan key:generate
```

Buat database kosong bernama `cv_tigaputrateknik` lewat DBeaver, lalu isi pengaturan `DB_*` di file `.env`. Setelah itu:

```bash
php artisan migrate --seed
php artisan storage:link
npm install
npm run dev
```

Folder proyek sebaiknya berada di direktori yang dikelola Herd supaya situs langsung terdaftar. Akun uji hasil seeder: `[diisi koordinator]`.

## Tim

| NIM | Nama | Peran |
|---|---|---|
| 250210501003 | Andi Muh. Yusran Haris | Koordinator: manajemen proyek, backend, database, desain |
| 250210501044 | Attahilla Ahmad Willem | Pembuat halaman |
| 250210501048 | Harry Kristianto | Pembuat halaman |
| 230210501072 | Adniel Pascal Ancelo Jonas | Pembuat halaman |
| 250210500021 | Firayanti | Pembuat halaman |

## Berkontribusi

Baca [CONTRIBUTING.md](CONTRIBUTING.md) sebelum membuat branch, commit, atau Pull Request.
