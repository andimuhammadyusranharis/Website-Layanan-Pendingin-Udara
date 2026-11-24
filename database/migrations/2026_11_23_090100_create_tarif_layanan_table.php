<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tarif per unit untuk setiap kombinasi layanan dan jenis AC.
     * Tarif kosong berarti layanan itu tidak tersedia untuk jenis AC tersebut.
     */
    public function up(): void
    {
        Schema::create('tarif_layanan', function (Blueprint $table) {
            $table->id('id_tarif');

            // Kombinasi layanan dan jenis AC. Hapus jenis AC atau layanan berarti tarif ikut hilang.
            $table->foreignId('id_layanan')->constrained('layanan', 'id_layanan')->cascadeOnDelete();
            $table->foreignId('id_jenis_ac')->constrained('jenis_ac', 'id_jenis_ac')->cascadeOnDelete();

            // Harga per unit dalam rupiah.
            $table->unsignedInteger('tarif')->nullable();

            $table->timestamps();

            $table->unique(['id_layanan', 'id_jenis_ac']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tarif_layanan');
    }
};
