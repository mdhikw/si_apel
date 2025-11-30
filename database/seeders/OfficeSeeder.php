<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // Wajib ada biar gak error

class OfficeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('offices')->insert([
            'name' => 'Polres Garut',
            'address' => 'Jl. Jendral Sudirman No.204, Sucikaler, Kec. Karangpawitan, Kabupaten Garut, Jawa Barat 44182',
            
            // KOORDINAT (Ganti dengan lokasi aslimu biar radiusnya valid)
            'latitude' => 7.211730178316391,  
            'longitude' => 107.91931119135107, 
            
            'radius' => 200, // Jarak toleransi dalam meter
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}