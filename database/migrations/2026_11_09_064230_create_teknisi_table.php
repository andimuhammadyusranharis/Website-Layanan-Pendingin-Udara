<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil teknisi. Jenis "pemilik" untuk admin yang juga turun ke lapangan (tanpa bagi hasil dan setoran),
     * "mitra" untuk teknisi luar. Teknisi mitra baru berstatus "menunggu" sampai disetujui admin.
     */
    public function up(): void
    {
        Schema::create('teknisi', function (Blueprint $table) {
            $table->id('id_teknisi');
            $table->foreignId('id_user')->unique()->constrained('users')->cascadeOnDelete();
            $table->enum('jenis', ['pemilik', 'mitra'])->default('mitra');
            $table->enum('status', ['menunggu', 'aktif', 'nonaktif'])->default('menunggu')->index();
            $table->string('domisili')->nullable();
            $table->string('sertifikasi')->nullable();
            $table->timestamp('disetujui_pada')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teknisi');
    }
};
