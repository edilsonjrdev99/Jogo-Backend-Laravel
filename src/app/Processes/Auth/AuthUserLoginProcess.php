<?php

namespace App\Processes\Auth;

use App\DMC\Auth\AuthUserLoginDMC;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthUserLoginProcess {
    /**
     * Responsável por verificar as credenciais e retornar o Token caso esteja certo ou false caso esteja errado
     * @param AuthUserLoginDMC $authUserLoginDMC - DMC de login de usuário
     * @return boll|string - Retorna false caso as credenciais estejam erradas ou o token caso estejam certas
     */
    public function exec(AuthUserLoginDMC $authUserLoginDMC): bool|string {
        return JWTAuth::attempt($authUserLoginDMC->toArray());
    }
}