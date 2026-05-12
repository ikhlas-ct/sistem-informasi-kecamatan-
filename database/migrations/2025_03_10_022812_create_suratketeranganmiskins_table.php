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
        Schema::create('suratketeranganmiskin', function (Blueprint $table) {
            $table->id('id_pelayanan');
            $table->unsignedBigInteger('id_masyarakat');
            $table->foreign('id_masyarakat')->references('id_masyarakat')->on('masyarakat')->onDelete('cascade');
            $table->unsignedBigInteger('id_pegawai')->nullable();
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('cascade');
            $table->text('alasan_pembuatan');
            $table->string('surat_pengantar_rt_rw');
            $table->string('surat_pernyataan_pribadi');

            $table->date('tanggal_pengajuan');
            $table->string('alasan_penolakan')->nullable();
             $table->double('pendapatan')->nullable();


            $table->enum('validasi_pegawai', ['diterima', 'ditolak','diproses'])->default('diproses');
            $table->enum('validasi_camat', ['diterima', 'ditolak','diproses'])->default('diproses');
            $table->enum('validasi_pengantar', ['diterima','ditolak','diproses'])->default('diproses');
            $table->enum('validasi_pernyataan', ['diterima','ditolak','diproses'])->default('diproses');
            $table->enum('status', ['pending', 'diproses', 'selesai', 'ditolak'])->default('pending');
            $table->boolean('arsip')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suratketeranganmiskin');
    }
};
