<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('offices', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama Kantor (Misal: Polres A, Pos Lantas B)
            $table->string('address'); // Alamat lengkap
            
            // Koordinat GPS (Latitude & Longitude)
            // Kita pakai tipe 'double' agar akurat
            $table->double('latitude'); 
            $table->double('longitude');
            
            // Radius Absen (Meter). Misal: 200 meter dari titik pusat
            $table->integer('radius')->default(200); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('offices');
    }
};