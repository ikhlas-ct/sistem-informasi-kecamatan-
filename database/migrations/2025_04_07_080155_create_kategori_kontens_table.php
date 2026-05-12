<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('kategori_konten', function (Blueprint $table) {
            $table->id('id_kategori_konten');
            $table->unsignedBigInteger('id_konten');
            $table->unsignedBigInteger('id_kategori');

            $table->foreign('id_konten')->references('id_konten')->on('konten')->onDelete('cascade');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kategori_konten');
    }
};
