<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_link', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_dokumen')
                  ->constrained('dokumen_bersama')
                  ->cascadeOnDelete();
            $table->string('judul', 255)->nullable(); // label/keterangan link
            $table->text('url');                      // URL drive / eksternal
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_link');
    }
};
