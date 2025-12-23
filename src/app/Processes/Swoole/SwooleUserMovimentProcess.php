<?php

namespace App\Processes\Swoole;

class SwooleUserMovimentProcess {
    /**
     * Armazena as posições dos usuários
     */
    public array $positionUsers;

    /**
     * Método responsável por executar o processo e notificar os clients das posições atuais dos jogadores
     * @param array $data - payload da posição do usuário
     * @param int $id = id da conexão do websocket (user->fd)
     */
    public function exec(array $data, int $id): void {
        $this->positionUsers[$id] = [
            'user' => $data['data']['name'],
            'position' => $data['data']['position']
        ];
    }
}
