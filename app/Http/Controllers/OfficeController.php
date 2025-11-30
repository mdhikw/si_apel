<?php

namespace App\Http\Controllers;

use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    // Menampilkan halaman tabel (yang ada di screenshot kamu)
    public function index()
    {
        $offices = Office::all();
        return view('offices.index', compact('offices'));
    }

    // 1. Membuka Form Tambah Kantor
    public function create()
    {
        return view('offices.create');
    }

    // 2. Menyimpan Data ke Database
   public function store(Request $request)
    {
        // 1. VALIDASI DIPERKETAT
        $request->validate([
            'name'      => 'required',
            'latitude'  => 'required',
            'longitude' => 'required',
            'address'   => 'required', // <--- TAMBAHKAN INI (Wajib Isi)
        ]);

        // 2. SIMPAN DATA
        Office::create([
            'name'      => $request->name,
            'address'   => $request->address, // Pastikan ini mengambil dari form
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius ?? 200,
        ]);

        return redirect()->route('offices.index')->with('success', 'Kantor berhasil ditambahkan!');
    }
    // --- FUNGSI BARU UNTUK EDIT & HAPUS ---

    // 3. Menampilkan Halaman Edit (Formulir dengan data lama)
    public function edit(Office $office)
    {
        return view('offices.edit', compact('office'));
    }

    // 4. Memproses Update Data
    public function update(Request $request, Office $office)
    {
        // Validasi input
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        // Update data di database
        $office->update([
            'name' => $request->name,
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
        ]);

        return redirect()->route('offices.index')->with('success', 'Data kantor berhasil diperbarui!');
    }

    // 5. Menghapus Data
    public function destroy(Office $office)
    {
        $office->delete();
        return redirect()->route('offices.index')->with('success', 'Data kantor berhasil dihapus!');
    }
}