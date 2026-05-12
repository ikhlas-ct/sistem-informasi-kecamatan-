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
        Schema::create('masyarakat', function (Blueprint $table) {
            $table->id('id_masyarakat');
            $table->string('nik', 16)->unique();
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('kk', 16);
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan'])->nullable();;
            $table->string('no_hp', 20);
            $table->string('nama_masyarakat', 100);
            $table->string('nama_ibu',255)->nullable();
            $table->text('alamat');
            $table->string('scan_ktp',255)->nullable();
            $table->string('scan_kk',255)->nullable();
            $table->string('foto_diri_ktp',255)->nullable();
            $table->string('foto_diri_kk', 255)->nullable();
            $table->string('akta_kelahiran', 255)->nullable();
            $table->string('foto_profil',255);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('masyarakat');
    }
};
