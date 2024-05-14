<?php

return [

    'jwt_key' => 'HashMart20221225!@#',

    // password salt
    'salt' => 'HashMart20230113@##$$%$',

    // 存储映射
    'store_config' => [
        'local' => '',
        'aliyun' => 'store_oss',
        'qiniu' => 'store_qiniu',
        'qcloud' => 'store_tencent'
    ],

    // 域名key和存储位置映射
    'store_domain' => [
        'aliyun' => 'oss_domain',
        'qiniu' => 'qiniu_domain',
        'qcloud' => 'tencent_domain'
    ],

    // hash累计值
    'hash_total' => 1048575,

    // 最小概率值
    'min_probability' => 0.0001,

    // 玩法id
    'play_id' => [
        1 => '一番赏',
        2 => '哈希赏',
        3 => '全随机',
        4 => '潮玩赏',
        5 => '无限赏',
    ],

    // redis队列进程数
    'worker_count' => 1,

    // 订单延迟时间 2分钟不支付
    'order_time' => 2 * 60,

    // 特殊赏种
    'special_reward' => [
        0 => '无限赏'
    ]
];
