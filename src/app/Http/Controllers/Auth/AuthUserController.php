<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use App\DMC\Auth\AuthUserLoginDMC;

use App\Http\Requests\Auth\AuthUserLoginRequest;

use App\Processes\Auth\AuthUserLoginProcess;

class AuthUserController extends Controller {
    public function __construct(
        private readonly AuthUserLoginProcess $authUserLoginProcess
    ) {}
    /**
     * Método responsável por realizar o login do usuário, settar o cookie e retornar a response
     * @return JsonResponse - Response informando se está logado ou não
     */
    public function login(AuthUserLoginRequest $request): JsonResponse {
        $credentials = new AuthUserLoginDMC($request->input('email'), $request->input('password'));

        $token = $this->authUserLoginProcess->exec($credentials);

        if(!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Credencial inválida.'
            ]);
        }

        $cookie = $this->setCookie($token);

        return response()->json([
            'success' => true,
            'message' => 'Login realizado com sucesso.'
        ])->withCookie($cookie);
    }

    /**
     * Método responsável por criar e retornar o cookie para o controller settar na response caso exista um token
     * @param string $token - Token do usuário
     * @return Cookie|null - Retorna o Cookie ou null caso não exista um token
     */
    private function setCookie(string $token): ?Cookie {
        if(!$token) return null;

        return cookie(
            name: 'auth_token',
            value: $token,
            minutes: 60 * 24,
            httpOnly: true,
            secure: true,
            sameSite: 'strict'
        );
    }
}
