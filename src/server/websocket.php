<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Swoole\Server\WebSocketServer;

/**
 * Responsável por startar o servidor WebSocket
 */
WebSocketServer::start();