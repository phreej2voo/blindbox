<?php

return [

    // 链接服务进程数 数字建议为 cpu核数
    'gateway_count' => 1,
    // 工作进程进程数 数字建议为 cpu核数 * 2
    'business_count' => 2,
    // 端口
    'ws_port' => 8080,
    // 注册中心端口
    'register_port' => 1238,
    // 开启ssl
    'ssl_open' => false,
    // 是否自签名证书
    'self_cert' => false, // 如果是自签名证书需要开启此选项,宝塔的自签名证书需要设置为true
    // ssl上下文
    'context' => [
        // 请使用绝对路径
        'local_cert' => '磁盘路径/server.pem', // 也可以是crt文件
        'local_pk' => '磁盘路径/server.key',
        'verify_peer' => false,
    ],
    // 内部api端口
    'api_port' => 9909
];