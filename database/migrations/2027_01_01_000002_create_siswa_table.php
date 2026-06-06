<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id('id_siswa');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_sekolah')->constrained('sekolah', 'id_sekolah')->onDelete('cascade');

            $table->string('nama_siswa');
            $table->string('nis', 20)->nullable();
            $table->string('kelas', 20)->nullable();
            $table->string('foto_profil')->nullable();

                      $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
