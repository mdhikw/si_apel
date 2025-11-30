<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    // Kolom yang boleh diisi oleh sistem
    protected $fillable = [
        'user_id',
        'date',
        'time_in',
        'time_out',
        'latlon_in',
        'latlon_out',
        'photo_in',
        'photo_out',
        'status',
    ];

    // --- RELASI KE USER (PENTING) ---
    // Fungsi ini memberitahu Laravel bahwa: 
    // "Satu data Absen ini -> Milik satu User"
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}