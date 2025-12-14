<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware {
    /**
     * Responsável por verificar se o usuário autenticado é admin
     * @param Request $request - request
     * @param Closure $next - fluxo da request
     * @return Response - Response do middleware
     */
    public function handle(Request $request, Closure $next): Response {
        // Verifica se o usuário está autenticado
        if(!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Não autenticado.'
            ], 401);
        }

        // Verifica se o usuário é admin
        if(!auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Acesso negado. Apenas administradores.'
            ], 403);
        }

        return $next($request);
    }
}
