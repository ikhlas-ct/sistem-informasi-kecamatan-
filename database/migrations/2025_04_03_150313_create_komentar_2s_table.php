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
        Schema::create('komentar', function (Blueprint $table) {
            $table->id('id_komentar');

            // Tidak menggunakan ->after('id_user')
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id_komentar')->on('komentar')->onDelete('cascade');
        
            $table->string('nama')->nullable(); 
            $table->string('alamat')->nullable();
            $table->string('no_hp')->nullable();
        
            $table->unsignedBigInteger('id_konten');
            $table->foreign('id_konten')->references('id_konten')->on('konten')->onDelete('cascade');
        
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
        
            $table->string('ip_address')->nullable();
            $table->text('isi_komentar');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('komentar');
    }
};
