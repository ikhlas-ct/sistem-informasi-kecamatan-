<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_lampiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dokumen')
                  ->constrained('dokumen_bersama')
                  ->cascadeOnDelete();
            $table->enum('tipe', ['file', 'foto']);
            $table->string('nama_asli', 255);   // nama asli dari user
            $table->string('nama_simpan', 255); // nama file di storage (unik/hash)
            $table->string('path', 500);        // path di disk
            $table->string('mime_type', 100)->nullable();
            $table->unsignedInteger('ukuran_kb')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_lampiran');
    }
};
