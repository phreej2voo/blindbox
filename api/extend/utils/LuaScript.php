<?php

namespace utils;

class LuaScript
{
    protected $redis;

    public function __construct()
    {
        $redisConf = config('redis');
        $this->redis = new \Redis();
        $this->redis->connect($redisConf['host'], $redisConf['port']);
        if (!empty($redisConf['auth'])) {
            $this->redis->auth($redisConf['auth']);
        }
        $this->redis->select($redisConf['db']);
    }

    /**
     * 获得锁,如果锁被占用,阻塞,直到获得锁或者超时。
     * -- 1、如果 $timeout 参数为 0,则立即返回锁。
     * -- 2、建议 timeout 设置为 0,避免 redis 因为阻塞导致性能下降。请根据实际需求进行设置。
     *
     * @param string $key 缓存KEY。
     * @param int $timeout 取锁超时时间。单位(秒)。等于0,如果当前锁被占用,则立即返回失败。如果大于0,则反复尝试获取锁直到达到该超时时间。
     * @param int $lockSecond 锁定时间。单位(秒)。
     * @param int $sleep 取锁间隔时间。单位(微秒)。当锁为占用状态时。每隔多久尝试去取锁。默认 0.1 秒一次取锁。
     * @return bool 成功:true、失败:false
     * @throws \Exception
     */
    public function lock(string $key, int $timeout = 0, int $lockSecond = 20, int $sleep = 100000): bool
    {
        if (strlen($key) === 0) {
            // 项目抛异常方法
            throw new \Exception(500, '缓存KEY没有设置');
        }
        $start = floatval($this->getMicroTime());

        do {
            // [1] 锁的 KEY 不存在时设置其值并把过期时间设置为指定的时间。锁的值并不重要。重要的是利用 Redis 的特性。
            $acquired = $this->redis->set("Lock:{$key}", 1, ['NX', 'EX' => $lockSecond]);
            if ($acquired) {
                break;
            }
            if ($timeout === 0) {
                break;
            }
            usleep($sleep);
        } while (!is_numeric($timeout) || ($this->getMicroTime()) < ($start + ($timeout * 1000000)));

        return (bool)$acquired;
    }

    /**
     * 释放锁
     *
     * @param mixed $key 被加锁的KEY。
     * @return void
     * @throws \Exception
     */
    public function release($key)
    {
        if (strlen($key) === 0) {
            // 项目抛异常方法
            throw new \Exception(500, '缓存KEY没有设置');
        }
        $this->redis->del("Lock:{$key}");
    }

    /**
     * 获取当前微秒。
     */
    public function getMicroTime(): string
    {
        return bcmul(microtime(true), 1000000);
    }
}