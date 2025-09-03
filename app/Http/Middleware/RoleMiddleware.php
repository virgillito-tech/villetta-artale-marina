<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Controlla il ruolo
        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Accesso negato: solo per amministratori');
                }
                break;
            case 'user':
                if (!$user->isUser()) {
                    abort(403, 'Accesso negato');
                }
                break;
            default:
                abort(403, 'Ruolo non valido');
        }

        return $next($request);
    }
}
