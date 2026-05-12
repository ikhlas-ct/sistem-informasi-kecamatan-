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
        Schema::create('konten', function (Blueprint $table) {
            $table->id('id_konten');
            $table->string('judul',255)->unique();
            $table->longText    ('isi');
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->string('slug')->unique();
            $table->timestamp('tanggal_publikasi')->useCurrent();
            $table->enum('jenis_konten', ['berita', 'artikel','seni_tari','makanan_daerah','Kerajinan_daerah','seni_musik','seni_budaya','pariwisata']);
            $table->unsignedBigInteger('id_kategori');
            $table->foreign('id_kategori')->references('id_kategori')->on('kategori')->onDelete('cascade');
            $table->string('gambar');
            $table->enum('status_notif', ['pending', 'diterima'])->default('pending');


            $table->boolean('aktif')->default(false);



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('konten');
    }
};
