<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mading', function (Blueprint $table) {
            $table->id('id_mading');
            $table->foreignId('id_user')->constrained('users', 'id')->onDelete('cascade');
            $table->foreignId('id_sekolah')->constrained('sekolah', 'id_sekolah')->onDelete('cascade');

            $table->string('judul');
            $table->longText('isi');
            $table->string('gambar')->nullable();
            $table->string('slug')->unique();

            // Jenis mading yang diposting
            $table->enum('jenis', ['karya', 'pengumuman', 'berita', 'cerpen', 'puisi', 'lainnya']);

            // Status publikasi (dikontrol poster)
            $table->enum('status', ['draft', 'publish'])->default('draft');

            // Approval oleh sekolah (khusus mading dari siswa)
            // approved → langsung jika yang posting adalah akun sekolah
            // pending  → jika yang posting adalah siswa, nunggu review sekolah
            // rejected → ditolak sekolah
            $table->enum('approval_status', ['approved', 'pending', 'rejected'])->default('pending');
            $table->text('alasan_penolakan')->nullable();

            $table->dateTime('tanggal_publikasi')->nullable();
            $table->unsignedBigInteger('views')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mading');
    }
};
