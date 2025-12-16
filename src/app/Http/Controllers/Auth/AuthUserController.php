<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Cookie;

use App\DMC\Auth\AuthUserLoginDMC;

use App\Http\Requests\Auth\AuthUserLoginRequest;

use App\Processes\Auth\AuthUserLoginProcess;
use App\Processes\Auth\AuthUserLogoutProcess;

class AuthUserController extends Controller {
    public function __construct(
        private readonly AuthUserLoginProcess $authUserLoginProcess,
        private readonly AuthUserLogoutProcess $authUserLogoutProcess
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
     * Responsável por deslogar um usuário inativando seu token e retornar a response
     * @return JsonResponse - Response informando se o usuário foi deslogado
     */
    public function logout(): JsonResponse {
        $invalidateCookie = $this->authUserLogoutProcess->exec();

        if(!$invalidateCookie) {
            return response()->json([
                'success' => false,
                'message' => 'Token inválido.'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Logout realizado com sucesso.'
        ])->withCookie($invalidateCookie);
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
            path: '/',
            domain: null,
            secure: false,
            httpOnly: true,
            raw: false,
            sameSite: 'Lax'
        );
    }
}
