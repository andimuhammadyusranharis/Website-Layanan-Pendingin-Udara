<?php

namespace App\Services;

/**
 * Isi halaman statis: kebijakan privasi serta syarat dan ketentuan.
 *
 * Teks mengikuti PRD: bagian 6.5.1 (halaman 20 dan 21), kriteria penerimaan pelanggan dan teknisi (7.1 dan 7.3),
 * aturan bisnis (8.1 sampai 8.5), kebutuhan non-fungsional (11), dan keputusan (13).
 * Bentuk datanya sama untuk kedua halaman supaya satu tampilan bisa dipakai bersama.
 */
class HalamanStatisService
{
    /**
     * Batas pembatalan pesanan jasa yang sudah dijadwalkan (PRD 8.3 dan 13).
     * Nilai ini nantinya diambil dari tabel pengaturan.
     */
    public const BATAS_BATAL_JAM = 12;

    /**
     * @return array{judul: string, ringkasan: string, bagian: list<array{judul: string, paragraf?: string, poin?: list<string>}>, tautan_lain: array{label: string, route: string}}
     */
    public function kebijakanPrivasi(): array
    {
        return [
            'judul' => 'Kebijakan privasi',
            'ringkasan' => 'Penjelasan singkat tentang data yang kami minta, untuk apa data itu dipakai, dan bagaimana kami menjaganya.',
            'bagian' => [
                [
                    'judul' => 'Data yang kami minta',
                    'poin' => [
                        'Saat mendaftar: nama, nomor telepon, alamat, email, dan kata sandi.',
                        'Saat memesan layanan: alamat, patokan lokasi, jenis dan merek AC, keluhan atau kebutuhan, serta foto bila Anda mengunggahnya.',
                        'Saat membayar lewat transfer: bukti transfer yang Anda unggah.',
                        'Saat menulis ulasan: rating dan komentar Anda.',
                    ],
                ],
                [
                    'judul' => 'Untuk apa data dipakai',
                    'poin' => [
                        'Memproses pesanan jasa dan pembelian produk.',
                        'Mengatur jadwal dan menugaskan teknisi.',
                        'Menghubungi Anda lewat telepon atau WhatsApp tentang pesanan.',
                        'Memeriksa pembayaran dan mencatat transaksi.',
                        'Menampilkan ulasan Anda di halaman Ulasan.',
                    ],
                ],
                [
                    'judul' => 'Siapa yang bisa melihat data Anda',
                    'poin' => [
                        'Pemilik usaha (admin) melihat data pelanggan dan pesanan untuk mengelola usaha.',
                        'Teknisi yang ditugaskan melihat alamat, patokan lokasi, jenis dan merek AC, keluhan, dan nomor telepon Anda untuk pekerjaan itu.',
                        'Pengunjung lain hanya melihat ulasan yang ditampilkan, beserta nama pemberi ulasan.',
                        'Bukti pembayaran tidak ditampilkan kepada pengunjung.',
                    ],
                ],
                [
                    'judul' => 'Cara kami menjaga data',
                    'poin' => [
                        'Kata sandi tidak disimpan sebagai teks biasa, jadi tidak bisa dibaca siapa pun.',
                        'Akses ke setiap halaman dibatasi menurut peran: pelanggan, teknisi, dan admin.',
                        'Bukti pembayaran disimpan di penyimpanan yang tertutup.',
                        'Jenis dan ukuran berkas yang diunggah dibatasi.',
                        'Data pelanggan dilindungi sesuai UU Perlindungan Data Pribadi (UU PDP).',
                    ],
                ],
                [
                    'judul' => 'Pertanyaan tentang data Anda',
                    'paragraf' => 'Hubungi kami lewat WhatsApp '.config('usaha.whatsapp.nomor').' atau datang ke toko di '.config('usaha.alamat').'. Jam buka: '.config('usaha.jam_buka').'.',
                ],
            ],
            'tautan_lain' => ['label' => 'Syarat dan ketentuan', 'route' => 'syarat'],
        ];
    }

    /**
     * @return array{judul: string, ringkasan: string, bagian: list<array{judul: string, paragraf?: string, poin?: list<string>}>, tautan_lain: array{label: string, route: string}}
     */
    public function syarat(): array
    {
        return [
            'judul' => 'Syarat dan ketentuan',
            'ringkasan' => 'Aturan pemesanan, pembayaran, dan pembatalan di '.config('usaha.nama').'. Baca sebelum memesan.',
            'bagian' => [
                [
                    'judul' => 'Area layanan dan cara memesan',
                    'poin' => [
                        'Layanan hanya tersedia untuk alamat di Kabupaten Pangkep.',
                        'Untuk memesan, Anda perlu masuk ke akun.',
                        'Pesan jasa dengan memilih layanan dan jenis AC, memilih jadwal, lalu membayar.',
                        'Produk yang stoknya habis tidak bisa dipesan.',
                    ],
                ],
                [
                    'judul' => 'Harga dan estimasi',
                    'poin' => [
                        'Estimasi biaya yang tampil sebelum konfirmasi adalah perkiraan dan bisa berubah setelah survei.',
                        'Total biaya adalah tarif layanan dikali jumlah unit, ditambah biaya tambahan, harga produk, dan ongkir.',
                        'Biaya tambahan di lokasi bisa diubah teknisi atau admin, disertai catatan alasan, lalu disampaikan kepada Anda.',
                        'Tarif dan harga yang berlaku adalah yang tampil saat Anda memesan. Perubahan harga setelahnya tidak mengubah pesanan Anda.',
                        'AC jenis central, multi split, VRF, dan ducted perlu survei lebih dulu. Yang bisa dipesan hanya konsultasi dan survei, biayanya diisi setelah survei.',
                    ],
                ],
                [
                    'judul' => 'Pembayaran',
                    'poin' => [
                        'Jasa dibayar setelah pekerjaan selesai, secara tunai atau transfer.',
                        'Untuk transfer, unggah bukti transfer. Admin memeriksanya sebelum pembayaran dinyatakan lunas.',
                        'Pembelian produk yang diambil di toko dibayar tunai.',
                    ],
                ],
                [
                    'judul' => 'Pengantaran produk',
                    'poin' => [
                        'Pengantaran hanya untuk alamat di Kabupaten Pangkep.',
                        'Ongkir dihitung dari jarak dan diberitahukan sebelum Anda membayar. Barang dikirim setelah pembayaran lunas.',
                        'Produk yang dipesan bersama jasa dibawa teknisi saat kunjungan, tanpa ongkir.',
                    ],
                ],
                [
                    'judul' => 'Pembatalan',
                    'paragraf' => 'Alasan pembatalan wajib diisi.',
                    'poin' => [
                        'Pesanan yang masih menunggu bisa dibatalkan kapan saja.',
                        'Pesanan jasa yang sudah dijadwalkan bisa dibatalkan sampai '.self::BATAS_BATAL_JAM.' jam sebelum jam kerja. Lewat dari itu, hubungi admin.',
                        'Pesanan yang sedang dikerjakan atau dikirim tidak bisa dibatalkan sendiri. Hubungi admin.',
                        'Pesanan yang sudah selesai atau dikonfirmasi tidak bisa dibatalkan. Bila ada keluhan, hubungi kami lewat WhatsApp.',
                        'Bila pembayaran sudah lunas lalu pesanan dibatalkan, admin mengembalikan dana secara manual.',
                    ],
                ],
                [
                    'judul' => 'Konfirmasi dan ulasan',
                    'poin' => [
                        'Setelah pekerjaan selesai, Anda diminta mengonfirmasi bahwa pekerjaan sudah sesuai.',
                        'Setelah konfirmasi, Anda bisa memberi rating 1 sampai 5 dan komentar.',
                        'Pemilik usaha dapat memilih ulasan mana yang ditampilkan di halaman Ulasan.',
                    ],
                ],
            ],
            'tautan_lain' => ['label' => 'Kebijakan privasi', 'route' => 'kebijakan-privasi'],
        ];
    }
}
