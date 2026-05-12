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
        Schema::table('masyarakat', function (Blueprint $table) {
            $table->fullText(['nama_masyarakat','nik'], 'masyarakat_nama_fulltext');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('masyarakat', function (Blueprint $table) {
            $table->dropFullText('masyarakat_nama_fulltext');
        });
    }
};
