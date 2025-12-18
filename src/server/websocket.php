<?php

require __DIR__ . '/../vendor/autoload.php';

use App\Swoole\Server\WebSocketServer;

WebSocketServer::start();