<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jenis AC yang bisa dipilih saat memilih layanan dan menghitung estimasi biaya.
     * Tarif untuk tiap jenis AC disimpan di tabel tarif_layanan.
     */
    public function up(): void
    {
        Schema::create('jenis_ac', function (Blueprint $table) {
            $table->id('id_jenis_ac');

            // Identitas dan teks tampilan.
            $table->string('nama', 80)->unique();
            $table->string('keterangan', 160)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jenis_ac');
    }
};
