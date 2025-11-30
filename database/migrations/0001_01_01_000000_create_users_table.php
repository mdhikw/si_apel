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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Lengkap Anggota
            $table->string('email')->unique(); // Email untuk Login
            
            // --- DATA KHUSUS POLRES (KITA TAMBAHKAN INI) ---
            $table->string('nrp')->unique()->nullable(); // Nomor Registrasi Pokok
            $table->string('pangkat')->nullable(); // Bripda, Ipda, dll
            $table->string('jabatan')->nullable(); // Staff, Kanit, dll
            $table->string('no_hp')->nullable();   // Nomor WA
            
            // Role: Admin (Provost) atau Anggota Biasa
            $table->enum('role', ['admin', 'anggota'])->default('anggota');
            
            // Foto Profil
            $table->string('foto')->nullable();
            // -----------------------------------------------

            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};