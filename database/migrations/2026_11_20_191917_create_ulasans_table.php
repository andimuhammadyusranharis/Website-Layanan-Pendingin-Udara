<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ulasans', function (Blueprint $table) {
            $table->id('id_ulasan');
            $table->string('nama_pelanggan');
            $table->unsignedTinyInteger('rating');
            $table->text('komentar');
            $table->boolean('tampil')->default(true);
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ulasans');
    }
};
