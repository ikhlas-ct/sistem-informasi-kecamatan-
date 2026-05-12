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
        Schema::create('reaksi', function (Blueprint $table) {
            $table->id('id_reaksi');
            $table->unsignedBigInteger('id_konten');
            $table->foreign('id_konten')->references('id_konten')->on('konten')->onDelete('cascade');
            $table->unsignedBigInteger('id_user')->nullable();
            $table->foreign('id_user')->references('id')->on('users')->onDelete('set null');
            $table->enum('jenis', ['suka', 'marah', 'sedih', 'senang', 'terkejut', 'lucu']);
            $table->string('ip_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rekasis');
    }
};
