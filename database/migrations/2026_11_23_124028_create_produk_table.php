<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Produk yang dijual di toko (komponen dan unit AC), dikelola admin dan ditampilkan di halaman Toko.
     * Riwayat stok menyusul di tabel terpisah.
     */
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id('id_produk');

            // Identitas dan teks tampilan. Slug dipakai di alamat halaman detail /toko/{slug}.
            $table->string('nama', 150)->unique();
            $table->string('slug', 170)->unique();
            $table->string('kategori', 30);
            $table->text('deskripsi');
            $table->string('gambar')->nullable();

            // Khusus unit AC. Untuk komponen dibiarkan kosong.
            $table->string('merek', 50)->nullable();
            $table->string('model', 50)->nullable();
            $table->json('spesifikasi')->nullable();

            // Harga dan stok. Harga dalam rupiah tanpa desimal.
            $table->unsignedInteger('harga');
            $table->unsignedInteger('stok')->default(0);
            $table->string('satuan', 30)->nullable();

            $table->boolean('aktif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
