<?php

return [
    'db' => env('redis.db', 0),
    'host' => env('redis.host', '127.0.0.1'),
    'port' => env('redis.port', 6379),
    'auth' => env('redis.auth', ''),

    // 队列名称
    'queue_name' => 'redis-time-queue'
];