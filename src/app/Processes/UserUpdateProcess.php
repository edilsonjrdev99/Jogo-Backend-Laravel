<?php

namespace App\Processes;

use App\DMC\UserUpdateDMC;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserUpdateProcess {
    /**
     * Responsável por executar o processo de atualizar um usuário
     * @param UserUpdateDMC - DMC de atualização de usuário
     * @return User - Collection de User
     */
    public function exec(UserUpdateDMC $dmc): User {
        $user = User::findOrFail($dmc->userId);

        $data = $dmc->toArray();

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        return $user->fresh();
    }
}
