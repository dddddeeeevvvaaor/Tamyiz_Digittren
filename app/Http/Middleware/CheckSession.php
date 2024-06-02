<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Login;

class CheckSession
{
    public function handle($request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        $login = Login::where('nama', $user->nama)->first();

        if ($login && now()->diffInMinutes($login->last_active) > config('session.lifetime')) {
            Auth::logout();
            return redirect('/login')->with('message', 'Sesi Anda telah berakhir.');
        }

        $login->last_active = now();
        $login->save();

        return $next($request);
    }
}
