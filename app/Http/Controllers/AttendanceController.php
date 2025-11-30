<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Pastikan ini ada
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * 1. MENAMPILKAN DAFTAR KANTOR (Halaman Depan Absensi)
     */
    public function index()
    {
        $offices = Office::all();
        return view('attendances.index', compact('offices'));
    }

    /**
     * 2. MENAMPILKAN FORM ABSEN (Kamera & Peta)
     */
    public function create(Office $office)
    {
        return view('attendances.create', compact('office'));
    }

    /**
     * 3. MENYIMPAN DATA ABSENSI (LOGIKA UTAMA)
     */
    public function store(Request $request)
    {
        // A. Validasi Input dari Frontend
        $request->validate([
            'latitude'  => 'required',
            'longitude' => 'required',
            'photo'     => 'required',
        ]);

        // B. Cek Lokasi & Radius (Validasi Server)
        $office = $this->findNearestOffice($request->latitude, $request->longitude);

        if (!$office) {
            return redirect()->back()->with('error', 'Gagal! Anda berada di luar jangkauan radius kantor manapun.');
        }

        // C. Proses Simpan Foto
        $photo = $request->photo;
        $photo = str_replace('data:image/jpeg;base64,', '', $photo);
        $photo = str_replace(' ', '+', $photo);
        
        $filename = 'attendance_' . Auth::id() . '_' . time() . '.png';
        
        Storage::disk('public')->put('attendances/' . $filename, base64_decode($photo));

        // D. Simpan Data ke Database
        $attendance = Attendance::create([
            'user_id'    => Auth::id(),
            'date'       => now()->toDateString(),
            'time_in'    => now()->toTimeString(),
            'latlon_in'  => $request->latitude . ',' . $request->longitude,
            'photo_in'   => $filename,
            'status'     => 'pending', // <--- PENTING: Status awal jadi 'pending'
        ]);

        // E. Redirect dengan pesan sukses
        return redirect()->route('dashboard')->with('success', 'Absensi berhasil dikirim! Menunggu persetujuan Admin.');
    }

    /**
     * 4. MENAMPILKAN RIWAYAT ABSENSI
     */
    public function history()
    {
        // PERBAIKAN: Menggunakan Auth::id() agar tidak error
        if (Auth::id() == 1 || Auth::user()->role == 'admin') {
            // Admin: Melihat semua data
            $attendances = Attendance::with('user')->latest()->get();
        } else {
            // User Biasa: Melihat data sendiri
            $attendances = Attendance::where('user_id', Auth::id())->latest()->get();
        }

        return view('attendances.history', compact('attendances'));
    }

    /**
     * FITUR BARU: Admin Dashboard - Lihat Absensi Pending (NOTIFICATION CENTER)
     */
    public function adminPending()
    {
        // Pastikan hanya admin yang bisa akses
        if(Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Ambil semua absensi dengan status pending
        $pendingAttendances = Attendance::with('user')
                                ->where('status', 'pending')
                                ->latest()
                                ->paginate(15);

        // Hitung total pending
        $pendingCount = Attendance::where('status', 'pending')->count();

        return view('attendances.admin-pending', compact('pendingAttendances', 'pendingCount'));
    }

    /**
     * 5. FITUR BARU: ADMIN MENYETUJUI ABSENSI (ACC)
     */
    public function approve(Attendance $attendance)
    {
        // Pastikan hanya admin yang boleh
        if(Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Update status jadi 'hadir'
        $attendance->update([
            'status' => 'hadir'
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil disetujui!');
    }

    /**
     * 6. FITUR BARU: ADMIN MENOLAK ABSENSI
     */
    public function reject(Attendance $attendance)
    {
        // Pastikan hanya admin yang boleh
        if(Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized action.');
        }

        // Update status jadi 'ditolak'
        $attendance->update([
            'status' => 'ditolak'
        ]);

        return redirect()->back()->with('success', 'Absensi berhasil ditolak!');
    }

    // =========================================================================
    // FUNGSI PEMBANTU (PRIVATE)
    // =========================================================================

    private function findNearestOffice($lat, $lng)
    {
        $offices = Office::all();
        
        foreach ($offices as $office) {
            $distance = $this->calculateDistance($lat, $lng, $office->latitude, $office->longitude);
            if ($distance <= $office->radius) {
                return $office;
            }
        }

        return null;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000; 

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c; 
    }
}