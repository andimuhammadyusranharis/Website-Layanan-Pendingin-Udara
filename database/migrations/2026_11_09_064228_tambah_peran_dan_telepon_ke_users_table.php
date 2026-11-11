<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah peran akun dan nomor WhatsApp. Nomor boleh kosong saat daftar dan dilengkapi di halaman profil.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_telepon', 20)->nullable()->unique()->after('email');
            $table->enum('role', ['pelanggan', 'teknisi', 'admin'])->default('pelanggan')->after('password')->index();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['no_telepon']);
            $table->dropIndex(['role']);
            $table->dropColumn(['no_telepon', 'role']);
        });
    }
};
