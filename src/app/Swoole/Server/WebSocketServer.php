<?php

namespace App\Swoole\Server;

use Swoole\WebSocket\Server;

class WebSocketServer {
    private static array $onlineUsers = [];
    private static array $chatMessages = [];

    public static function start(): void {
        $config = require __DIR__ . '/../Config/websocket.php';

        $server = new Server(
            $config['host'],
            $config['port']
        );

        $server->set($config['server']);

        // Conexão do client
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

        // Notificações do client
        $server->on('message', function (Server $server, $request) {
            $data = json_decode($request->data, true);
            $type = $data['type'];

            if(!$data) return;

            echo "Nova notificação do client {$request->fd} do tipo {$type}" . PHP_EOL;

            switch($type){
                case 'set_user':
                    self::$onlineUsers[$request->fd]['name'] = $data['data']['name'];
                    break;
                case 'chat_message':
                    $user = self::$onlineUsers[$request->fd] ?? null;

                    if(!$user || empty($user['name'])) return;

                    self::$chatMessages[] = [
                        'user' => $user['name'],
                        'message' => $data['data']['message'],
                        'time' => date('H:i:s')
                    ];
                    break;
            }

            // Devolve para os clients conectados
            foreach(self::$onlineUsers as $fd => $user){
                if($server->isEstablished($fd)){
                    if($type == 'set_user') {
                        $server->push($fd, json_encode([
                            'type' => 'users_online',
                            'data' => array_values(self::$onlineUsers)
                        ]));
                    }

                    if($type == 'chat_message'){
                        $server->push($fd, json_encode([
                            'type' => 'chat_update',
                            'data' => self::$chatMessages
                        ]));
                    }
                }
            }
        });

        // Desconectado
        $server->on('close', function (Server $server, $fd) {
            unset(self::$onlineUsers[$fd]);

            echo "Usuário {$fd} se desconectou" . PHP_EOL;

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