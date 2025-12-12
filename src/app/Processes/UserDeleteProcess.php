<?php

namespace App\Processes;

use App\DMC\UserDeleteDMC;
use App\Models\User;

class UserDeleteProcess {
    /**
     * Responsável por executar o processo de excluir um usuário
     * @param UserDeleteDMC - DMC de exclusão de usuário
     * @return bool - indica se deu sucesso ou erro
     */
    public function exec(UserDeleteDMC $dmc): bool {
        $user = User::findOrFail($dmc->userId);

        return $user->delete();
    }
}
