<?php

namespace App\Swoole\Server;

use Swoole\WebSocket\Server;
use Swoole\Http\Request;
use Swoole\Http\Response;

class WebSocketServer {
    private static array $onlineUsers = [];

    public static function start(): void {
        $config = require __DIR__ . '/../Config/websocker.php';

        $server = new Server(
            $config['host'],
            $config['port']
        );

        $server->set($config['server']);

        // Cliente conectou
        $server->on('open', function (Server $server, $request) {
            self::$onlineUsers[$request->fd] = [
                'fd' => $request->fd
            ];

            echo "Usuário acabou de conectar, id da conexão: {$request->fd}" . PHP_EOL;

            $server->push($request->fd, json_encode([
                'type' => 'users_onlyne',
                'data' => array_values(self::$onlineUsers)
            ]));
        });

        // Recebe a mensagem
        $server->on('message', function (Server $server, $frame) {
            $data = json_decode($frame->data, true);

            if(!$data) return;

            switch($data['type']){
                case 'set_user':
                    // Front informa quem é o usuário
                    self::$onlineUsers[$frame->fd]['name'] = $data['data']['name'];
                break;
            }

            foreach(self::$onlineUsers as $fd => $user){
                if($server->isEstablished($fd)){
                    $server->push($fd, json_encode([
                        'type' => 'users_online',
                        'data' => array_values(self::$onlineUsers)
                    ]));
                }
            }
        });

        // Cliente desconectou
        $server->on('close', function (Server $server, $fd) {
            unset(self::$onlineUsers[$fd]);

            echo "Usuário {$fd} acabou de sair" . PHP_EOL;

            foreach(self::$onlineUsers as $clientFd => $user){
                if($server->isEstablished($clientFd)){
                    $server->push($clientFd, json_encode([
                        'type' => 'users_online',
                        'data' => array_values(self::$onlineUsers)
                    ]));
                }
            }
        });

        echo 'Servidor iniciado' . PHP_EOL;

        $server->start();
    }
}