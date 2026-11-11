<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Profil pelanggan. Satu akun pelanggan punya satu profil. Alamat dilengkapi di halaman profil.
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->id('id_pelanggan');
            $table->foreignId('id_user')->unique()->constrained('users')->cascadeOnDelete();
            $table->text('alamat')->nullable();
            $table->string('patokan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
};
