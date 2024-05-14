<?php

namespace utils;

class MyRedis
{
    protected $redis;

    public function __construct()
    {
        $this->redis = new \Redis();
        $redisConfig = config('redis');

        $this->redis->connect($redisConfig['host'], $redisConfig['port']);
        if (!empty($redisConfig['auth'])) {
            $this->redis->auth($redisConfig['auth']);
        }
        $this->redis->select($redisConfig['db']);
    }

    public function getObject()
    {
        return $this->redis;
    }
}