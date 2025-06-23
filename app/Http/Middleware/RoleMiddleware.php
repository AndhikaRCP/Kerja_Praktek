<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
class RoleMiddleware
{

public function handle($request, Closure $next, ...$roles)
{
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    $currentRole = Auth::user()->role;
    Log::info("Role Saat Ini: $currentRole | Diperbolehkan: " . implode(', ', $roles));

    if (!in_array($currentRole, $roles)) {
        abort(403, 'Akses tidak diizinkan');
    }

    return $next($request);
}

}
