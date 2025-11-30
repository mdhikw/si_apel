<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // <--- Wajib ada biar ga merah
use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',       // Nama shift (Pagi/Siang/Malam)
        'start_time', // Jam masuk
        'end_time',   // Jam pulang
    ];
}