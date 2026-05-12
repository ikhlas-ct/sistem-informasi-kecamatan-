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
        Schema::create('kecamatansetting', function (Blueprint $table) {
            $table->id('id_kecamatan');
               $table->string('nama_kecamatan', 255)->nullable();
               $table->string('kode_kecamatan', 50)->nullable();
               $table->string('kode_pos_kecamatan', 10)->nullable();
               $table->unsignedBigInteger('id_pegawai')->nullable();
               $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawai')->onDelete('set null');
               $table->text('alamat_kecamatan')->nullable();
               $table->string('email_kecamatan', 255)->nullable();
               $table->string('nomor_telepon_kecamatan', 20)->nullable();
               $table->string('nama_kabupaten', 255)->nullable();
               $table->string('kode_kabupaten', 50)->nullable();
               $table->string('provinsi', 255)->nullable();
               $table->string('kode_provinsi', 50)->nullable();
               $table->string('logo', 255)->nullable();
               $table->string('social_facebook', 255)->nullable();
               $table->string('social_instagram', 255)->nullable();
               $table->string('social_twitter', 255)->nullable();
               $table->text('visi_misi')->nullable();
               $table->longText('sejarah')->nullable();
               $table->longText('geografis')->nullable();
               $table->longText('tugas_pokok')->nullable();
               $table->longText('fungsi')->nullable();
               $table->longText('uraian_tugas')->nullable();

               $table->string('title_pengantar',255)->nullable();
               $table->longText('paragraf_pengantar')->nullable();
               $table->string('gambar_pengantar',255)->nullable();
               $table->string('gambar_struktur',255)->nullable();






               $table->timestamps();
           });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kecamatansettings');
    }
};
