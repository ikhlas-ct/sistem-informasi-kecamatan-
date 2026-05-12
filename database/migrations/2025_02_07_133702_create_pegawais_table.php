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
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->enum('role', ['pegawai', 'camat']);
            $table->string('nama_pegawai');
            $table->string('alamat_pegawai')->nullable();
            $table->string('nohp_pegawai', 20)->nullable();
            $table->string('email_pegawai')->nullable();
            $table->text('deskripsi')->nullable();
            $table->string('nik', 16)->unique();
            $table->string('nip', 20)->unique();
            $table->text('instagram')->nullable();
            $table->text('twitter')->nullable();
            $table->text('facebook')->nullable();


            $table->enum('jenis_kelamin', ['Laki-laki', 'Perempuan']);
            $table->string('pangkat_golongan',255);
            $table->string('jabatan',255);
            $table->string('foto_profil',255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawai');
    }
};
