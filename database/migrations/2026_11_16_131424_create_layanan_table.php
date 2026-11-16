<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Layanan yang ditawarkan, dikelola admin dan ditampilkan ke pelanggan.
     * Tarif per jenis AC menyusul di tabel terpisah.
     */
    public function up(): void
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->id('id_layanan');

            // Identitas dan teks tampilan. Slug dipakai di alamat halaman detail.
            $table->string('nama', 100)->unique();
            $table->string('slug', 120)->unique();
            $table->string('ringkasan', 160);
            $table->text('deskripsi');
            $table->string('kategori', 30)->nullable();
            $table->string('jenis_ac')->nullable();
            $table->string('durasi', 60)->nullable();
            $table->json('fitur')->nullable();
            $table->string('gambar')->nullable();

            // Harga. Tarif kosong untuk layanan yang perlu survei dulu.
            $table->unsignedInteger('tarif_mulai')->nullable();
            $table->string('satuan', 30)->nullable();
            $table->string('keterangan_harga', 50)->default('Mulai dari');
            $table->string('catatan_harga')->nullable();
            $table->boolean('perlu_survei')->default(false);

            // Pengaturan internal.
            $table->unsignedTinyInteger('bagi_hasil');
            $table->unsignedSmallInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('layanan');
    }
};
