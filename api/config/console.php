<?php
// +----------------------------------------------------------------------
// | 控制台配置
// +----------------------------------------------------------------------
return [
    // 指令定义
    'commands' => [
        'crontab' => 'app\\command\\Crontab',
        'blindboxOrder' => 'app\\command\\task\\BlindboxOrderCheck',
        'balanceOrder' => 'app\\command\\task\\BalanceOrderCheck',
        "redis" => 'app\\command\\Redis',
        "websocket" => 'app\\command\\Websocket',
        'roll' => 'app\\command\\task\\RollCheck'
    ],
];
