<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tambah id_mading nullable di tabel komentar
        Schema::table('komentar', function (Blueprint $table) {
            $table->unsignedBigInteger('id_mading')->nullable()->after('id_konten');
            $table->foreign('id_mading')->references('id_mading')->on('mading')->onDelete('cascade');

            // id_konten dijadikan nullable karena komentar sekarang bisa
            // untuk konten biasa ATAU mading (salah satu harus diisi)
            $table->unsignedBigInteger('id_konten')->nullable()->change();
        });

        // Tambah id_mading nullable di tabel reaksi
        Schema::table('reaksi', function (Blueprint $table) {
            $table->unsignedBigInteger('id_mading')->nullable()->after('id_konten');
            $table->foreign('id_mading')->references('id_mading')->on('mading')->onDelete('cascade');

            // id_konten dijadikan nullable karena reaksi sekarang bisa
            // untuk konten biasa ATAU mading (salah satu harus diisi)
            $table->unsignedBigInteger('id_konten')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('komentar', function (Blueprint $table) {
            $table->dropForeign(['id_mading']);
            $table->dropColumn('id_mading');
        });

        Schema::table('reaksi', function (Blueprint $table) {
            $table->dropForeign(['id_mading']);
            $table->dropColumn('id_mading');
        });
    }
};
