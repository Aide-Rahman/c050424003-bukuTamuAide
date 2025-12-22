<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class LogoutIfViaRemember
{
    /**
     * Jika user terautentikasi lewat "remember me" cookie,
     * paksa logout supaya setiap masuk web harus login ulang.
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::viaRemember()) {
            $recallerName = Auth::guard()->getRecallerName();

            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            Cookie::queue(Cookie::forget($recallerName));

            return redirect()->route('login');
        }

        return $next($request);
    }
}
