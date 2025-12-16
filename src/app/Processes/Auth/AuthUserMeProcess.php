<?php

namespace App\Processes\Auth;

use Illuminate\Support\Facades\Auth;

class AuthUserMeProcess {
    /**
     * Responsável por executar o método principal que retorna o usuário autenticado
     */
    public function exec() {
        return Auth::user();
    }
}