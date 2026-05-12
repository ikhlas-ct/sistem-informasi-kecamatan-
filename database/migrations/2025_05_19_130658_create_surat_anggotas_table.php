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
        Schema::create('surat_anggota', function (Blueprint $table) {
         $table->id();
        $table->unsignedBigInteger('id_pelayanan');
        $table->foreign('id_pelayanan')->references('id_pelayanan')->on('suratketeranganmiskin')->onDelete('cascade');
            $table->string('nama');
            $table->enum('jk', ['L','P']);
            $table->integer('umur');
            $table->string('hubungan');
            $table->string('pekerjaan');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat_anggota');
    }
};
