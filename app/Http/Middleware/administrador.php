<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class administrador
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = \PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            return response()->json(['error' => 'No tienes permiso para esta acción'], 401);
        }

        if ($user && ($user->rol_id == 1 || $user->rol_id == 2)) {
            return $next($request);
        }

        return response()->json(['error' => 'No tienes permiso para esta acción'], 401);
    }
}
