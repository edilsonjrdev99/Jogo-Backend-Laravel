<?php

namespace App\Processes;

use App\DMC\UserCreateDMC;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserCreateProcess {
    /**
     * Responsável por executar o processo de criar usuários
     * @param UserCreateDMC $dmc - DMC de criação de usuário
     * @return User - Collection de User
     */
    public function exec(UserCreateDMC $dmc): User {
        $data = $dmc->toArray();
        $data['password'] = Hash::make($dmc->password);

        return User::create($data);
    }
}
