<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Todos podem visualizar lista de usuários (público)
     */
    public function viewAny(?User $user): bool {
        return true;
    }

    /**
     * Todos podem visualizar detalhes de usuário (público)
     */
    public function view(?User $user, User $model): bool {
        return true;
    }

    /**
     * Admin pode atualizar qualquer usuário, não-admin pode atualizar apenas próprio perfil
     */
    public function update(User $user, User $model): bool {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $model->id;
    }

    /**
     * Apenas admins podem deletar usuários
     */
    public function delete(User $user, User $model): bool {
        // Previne admin de deletar a si mesmo
        if ($user->id === $model->id) {
            return false;
        }

        return $user->isAdmin();
    }
}
