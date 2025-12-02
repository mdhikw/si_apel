<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Office;
use App\Models\Shift;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. BUAT DATA KANTOR (LOKASI POLRES)
        // Ganti lat/long ini dengan koordinat sekolahmu/rumahmu nanti biar real
        Office::create([
            'name' => 'Polres Garut',
            'address' => 'Jl. Jendral Sudirman No.204, Sucikaler, Kec. Karangpawitan, Kabupaten Garut, Jawa Barat 44182',
            'latitude' => 7.211628793381207, // Contoh koordinat Jakarta
            'longitude' => 107.91935391333283,
            'radius' => 200, // Radius 200 meter
        ]);

        // 2. BUAT DATA SHIFT KERJA
        Shift::create([
            'name' => 'Shift Pagi',
            'start_time' => '08:00:00',
            'end_time' => '16:00:00',
        ]);

        Shift::create([
            'name' => 'Shift Siang',
            'start_time' => '14:00:00',
            'end_time' => '22:00:00',
        ]);

        Shift::create([
            'name' => 'Shift Malam',
            'start_time' => '20:00:00',
            'end_time' => '08:00:00',
        ]);

        // 3. BUAT AKUN ADMIN (SANGAT PENTING UTK LOGIN)
        User::create([
            'name' => 'Komandan Admin',
            'email' => 'admin@polres.com',
            'password' => Hash::make('12345678'), // Passwordnya ini
            'role' => 'admin',
            'nrp' => '12345678',
            'pangkat' => 'Kombes',
            'jabatan' => 'Kepala Resort',
        ]);

        // 4. BUAT AKUN ANGGOTA BIASA (UTK TES ABSEN)
        User::create([
            'name' => 'Bripda Budi',
            'email' => 'budi@polres.com',
            'password' => Hash::make('12345678'),
            'role' => 'anggota',
            'nrp' => '87654321',
            'pangkat' => 'Bripda',
            'jabatan' => 'Anggota Ops',
        ]);
    }
}
