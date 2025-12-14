<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class JwtFromCookie {
    /**
     * Responsável por verificar se existe um Token no cookie e setar no header caso não exista
     * @param Request $request - request
     * @param Clousure $next - fluxo da request
     * @return Response - Response do middreware
     */
    public function handle(Request $request, Closure $next): Response {
        // Se não tiver Authorization header, tenta pegar do cookie
        if (!$request->bearerToken() && $request->cookie('auth_token')) {
            $token = $request->cookie('auth_token');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }

        return $next($request);
    }
}
