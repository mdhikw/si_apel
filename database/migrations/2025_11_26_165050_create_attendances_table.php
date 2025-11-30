<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            
            // Relasi: Absen ini punya siapa? (Terhubung ke tabel Users)
            // cascadeOnDelete artinya: Kalau usernya dihapus, data absennya ikut terhapus (biar bersih)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->date('date'); // Tanggal absen
            $table->time('time_in'); // Jam masuk
            $table->time('time_out')->nullable(); // Jam pulang (Boleh kosong dulu saat baru datang)
            
            // Koordinat (Latitude, Longitude) saat absen masuk & pulang
            $table->string('latlon_in')->nullable();
            $table->string('latlon_out')->nullable();
            
            // Status: pending, hadir, ditolak
            $table->string('status')->default('pending');
            
            // Foto Bukti
            $table->string('photo_in')->nullable();
            $table->string('photo_out')->nullable();
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};