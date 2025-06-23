<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{

    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        logger([
            'Role terdeteksi' => Auth::user()->role,
            'Diizinkan untuk' => $roles,
        ]);



        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Akses tidak diizinkan');
        }


        return $next($request);
    }
}
