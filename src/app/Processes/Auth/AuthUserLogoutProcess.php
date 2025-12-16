<?php

namespace App\Processes\Auth;

use Symfony\Component\HttpFoundation\Cookie;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserLogoutProcess {
    /**
     * Responsável por executar o método principal que invalida o Token do usuário
     * @return Cookie|null - Se existir um token de usuário logado retorna um novo expirado, se não retorna null
     */
    public function exec(): ?Cookie {
        $token = JWTAuth::getToken();

        if(!$token) return null;

        JWTAuth::invalidate($token);

        return cookie(
            name: 'auth_token',
            value: '',
            minutes: -1,
            path: '/',
            domain: null,
            secure: false,
            httpOnly: true,
            raw: false,
            sameSite: 'Lax'
        );
    }
}