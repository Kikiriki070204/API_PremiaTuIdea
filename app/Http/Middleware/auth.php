<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class auth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json(['error' => 'Usuario no encontrado'], 404);
        }

        if ($user->password == null) {
            return response()->json(['error' => 'Registrese primero'], 422);
        }

        if (!$user->is_active) {
            return response()->json(['admin_active' => 'Su cuenta ha sido desactivada por los administradores'], 403);
        }

        return $next($request);
    }
}
