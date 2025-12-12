<?php

namespace App\Processes;

use App\Models\User;

class UserShowProcess {
    /**
     * Responsável por executar o processo de retornar o usuário pelo id
     * @param int $userId - id do usuário que quer retornar
     * @return User - Collection de User
     */
    public function exec(int $userId): User {
        return User::findOrFail($userId);
    }
}
