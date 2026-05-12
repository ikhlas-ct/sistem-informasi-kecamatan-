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
        Schema::create('lampiran_balasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_balasanpengaduan');
            $table->foreign('id_balasanpengaduan')->references('id_balasanpengaduan')->on('balasanpengaduan')->onDelete('cascade');
            $table->enum('tipe', ['gambar', 'file']);
            $table->string('path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lampiran_balasan');
    }
};
