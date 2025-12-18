<?php

return [
    'host'   => env('SWOOLE_WS_HOST', '0.0.0.0'),
    'port'   => env('SWOOLE_WS_PORT', 9501),
    'server' => [
        'worker_num'               => 1,
        'daemonize'                => false,
        'max_conn'                 => 10,
        'heartbeat_idle_time'      => 300,
        'heartbeat_check_interval' => 30
    ]
];