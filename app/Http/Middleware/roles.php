<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class roles
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

        if ($user->rol_id == 5) {
            return response()->json(['error' => 'No tienes permiso para esta acción'], 401);
        }

        return $next($request);
    }
}
