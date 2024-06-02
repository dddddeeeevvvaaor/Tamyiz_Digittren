<?php
// app/Http/Middleware/CheckSessionTimeout.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Login;

class CheckSessionTimeout
{
    public function handle($request, Closure $next)
    {
        // Memeriksa apakah pengguna terautentikasi
        if (Auth::check() && session()->has('lastActivityTime')) {
            if (now()->diffInMinutes(session('lastActivityTime')) >= config('session.lifetime')) {
                $user = Auth::user();
                Login::where('nama', $user->nama)->update(['is_online' => false]);
                // Memeriksa peran pengguna dan melakukan update
                // if ($user->role == 'student' && $user->santri) {
                //     Login::where('nama', $user->santri->nama_santri)->update(['is_online' => false]);
                // } elseif ($user->role == 'admin' && $user->admin) {
                //     Login::where('nama', $user->admin->nama_admin)->update(['is_online' => false]);
                // }
            }
        }

        session(['lastActivityTime' => now()]);
        return $next($request);
    }
}
