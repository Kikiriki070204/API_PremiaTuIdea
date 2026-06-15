<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class auth
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'No autorizado'], 401);
        }

        if (!$user) {
            return response()->json(['error' => 'No autorizado'], 401);
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
