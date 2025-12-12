<?php

namespace App\Processes;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class UserListProcess {
    /**
     * Responsável por executar o processo de listar todos os usuários por ordem de criação (Novos vem primeiro)
     * @param int $perPage - quantidade de itens por página
     * @return LengthAwarePaginator - Users com paginação
     */
    public function exec(int $perPage = 10): LengthAwarePaginator {
        return User::query()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }
}
