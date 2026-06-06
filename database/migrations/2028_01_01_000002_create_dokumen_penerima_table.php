<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_penerima', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dokumen')
                  ->constrained('dokumen_bersama')
                  ->cascadeOnDelete();
            $table->foreignId('id_user')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->boolean('izin_download')->default(true);
            $table->boolean('izin_lihat')->default(true);
            $table->boolean('sudah_dibaca')->default(false);
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();

            // Satu user tidak bisa jadi penerima duplikat pada dokumen yang sama
            $table->unique(['id_dokumen', 'id_user']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_penerima');
    }
};
