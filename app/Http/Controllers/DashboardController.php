<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Office;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Ambil Data Statistik Umum
        $totalKantor = Office::count();
        $totalPegawai = User::where('id', '!=', 1)->count(); // Asumsi ID 1 adalah Admin
        
        // 2. Data Absensi Hari Ini
        $hadirHariIni = Attendance::whereDate('date', now()->toDateString())->count();
        
        // 3. Data Khusus User yang Sedang Login
        $riwayatBulanIni = Attendance::where('user_id', Auth::id())
                            ->whereMonth('date', now()->month)
                            ->get();

        // 4. BARU: Untuk Admin - Hitung pending absensi
        $pendingCount = 0;
        if(Auth::user()->role == 'admin') {
            $pendingCount = Attendance::where('status', 'pending')->count();
        }

        return view('dashboard', compact('totalKantor', 'totalPegawai', 'hadirHariIni', 'riwayatBulanIni', 'pendingCount'));
    }
}