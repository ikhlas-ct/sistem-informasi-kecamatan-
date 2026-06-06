<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dokumen_bersama', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')
                  ->constrained('users')
                  ->cascadeOnDelete();
            $table->string('judul', 255);
            $table->text('deskripsi')->nullable();
            $table->enum('status', ['aktif', 'arsip'])->default('aktif');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dokumen_bersama');
    }
};
