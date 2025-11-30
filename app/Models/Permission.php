<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date_permission',
        'reason',      // Alasan izin
        'image',       // Bukti foto surat dokter/tugas
        'is_approved', // Status disetujui atau belum
    ];

    // --- RELASI KE USER ---
    // Fungsi ini menghubungkan data Izin dengan data Polisi
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}