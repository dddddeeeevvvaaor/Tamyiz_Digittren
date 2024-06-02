<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class OneDevice
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cek apakah user agent sama dengan sebelumnya  
            if ($user->last_user_agent && $user->last_user_agent !== $request->header('User-Agent')) {
                Auth::logout(); // Logout user saat ada perangkat baru yang login
                $user->last_user_agent = $request->header('User-Agent'); // Update last_user_agent dengan perangkat terbaru
                $user->save();

                return redirect('/login');
            }

            // Simpan user agent  
            if (!$user->last_user_agent || $user->last_user_agent !== $request->header('User-Agent')) {
                $user->last_user_agent = $request->header('User-Agent');
                $user->save();
            }

            // Tandai user sudah login  
            $user->loggedIn();
        }

        return $next($request);
    }
}
