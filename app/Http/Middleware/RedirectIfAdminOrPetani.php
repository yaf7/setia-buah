<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminOrPetani
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && in_array($user->role, ['admin', 'petani'], true)) {
            $route = $user->role === 'admin' ? 'admin.dashboard' : 'petani.dashboard';
            return redirect()->route($route);
        }

        return $next($request);
    }
}
