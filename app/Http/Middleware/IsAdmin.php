<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        // Cek apakah user sudah login DAN role-nya adalah admin
        if (Auth::check() && Auth::user()->role == 'admin') {
            return $next($request); // Silakan lewat
        }

        // Kalau bukan admin, tendang ke dashboard dengan pesan error
        return redirect('/dashboard')->with('error', 'Anda tidak memiliki akses Admin!');
    }
}