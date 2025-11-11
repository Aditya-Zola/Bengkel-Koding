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
        Schema::create('jadwal_periksa', function (Blueprint $table) {
             $table->id();

            // 1. Foreign Key ke tabel users (untuk id_dokter)
            $table->foreignId('id_dokter')->constrained('users')->onDelete('cascade');
            // 2. Kolom Hari (untuk sorting)
            $table->string('hari');
            // 3. Jam Mulai dan Jam Selesai
            $table->time('jam_mulai');
            $table->time('jam_selesai');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_periksa');
    }
};
