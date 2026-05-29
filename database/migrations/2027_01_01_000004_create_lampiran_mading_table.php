<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lampiran_mading', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_mading')->constrained('mading', 'id_mading')->onDelete('cascade');

            // Tipe file lampiran
            $table->enum('tipe', ['image', 'pdf', 'video', 'lainnya']);
            $table->string('path');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lampiran_mading');
    }
};
