<?php

namespace App\Http\Controllers;

use App\Models\Shift; // <--- PENTING: Panggil Model Shift
use Illuminate\Http\Request;

class ShiftController extends Controller
{
    /**
     * Menampilkan daftar semua shift.
     */
    public function index()
    {
        // 1. Ambil semua data dari tabel shifts
        $shifts = Shift::all();
        
        // 2. Kirim data tersebut ke tampilan (view) 'shifts.index'
        return view('shifts.index', compact('shifts'));
    }
}