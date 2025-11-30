<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            
            // Punya siapa?
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            $table->date('date_permission'); // Tanggal izin
            $table->text('reason'); // Alasan (Sakit/Dinas Luar/Cuti)
            $table->string('image')->nullable(); // Bukti Foto (Surat Dokter/Surat Tugas)
            
            // Status Persetujuan Admin
            // false = Menunggu Konfirmasi, true = Disetujui
            $table->boolean('is_approved')->default(false);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};