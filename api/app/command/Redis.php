<?php
declare (strict_types=1);

namespace app\command;

use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use think\facade\Log;
use Workerman\MySQL\Connection;
use Workerman\RedisQueue\Client;
use Workerman\Worker;

class Redis extends Command
{
    // 修改命令文件
    protected function configure()
    {
        // 指令配置
        $this->setName('redis')
            ->addArgument('status', Argument::REQUIRED, 'start/stop/reload/status')
            ->addOption('d', null, Option::VALUE_NONE, 'daemon（守护进程）方式启动')
            ->setDescription('start/stop/restart loadItem');
    }

    protected function init(Input $input, Output $output)
    {
        global $argv;

        $argv[1] = $input->getArgument('status') ?: 'start';
        if ($input->hasOption('d')) {
            $argv[2] = '-d';
        } else {
            unset($argv[2]);
        }
    }

    protected function execute(Input $input, Output $output)
    {
        $this->init($input, $output);

        $logo =<<<EOL
 _               _                          _
| |__   __ _ ___| |__  _ __ ___   __ _ _ __| |_
| '_ \ / _` / __| '_ \| '_ ` _ \ / _` | '__| __|
| | | | (_| \__ \ | | | | | | | | (_| | |  | |_
|_| |_|\__,_|___/_| |_|_| |_| |_|\__,_|_|   \__|
EOL;

        $output->writeln($logo . PHP_EOL);

        $worker = new Worker();
        $worker->count = config('shop.worker_count');
        $worker::$pidFile = rtrim(root_path(), '/') . '/runtime/redisTime.pid';
        $worker::$logFile = rtrim(root_path(), '/') . '/runtime/redisTime.log';
        $worker->name = 'redisTime';
        $worker->onWorkerStart = [$this, 'start'];
        $worker->runAll();
    }

    public function start($work)
    {
        $dbConf = config('database.connections.mysql');
        $db = new Connection($dbConf['hostname'], $dbConf['hostport'],
            $dbConf['username'], $dbConf['password'], $dbConf['database']);
        $prefix = $dbConf['prefix'];

        $redisConf = config('redis');
        $options = [
            'db' => $redisConf['db']
        ];

        $redisObj = new \Workerman\Redis\Client('redis://' . $redisConf['host'] . ":" . $redisConf['port']);
        if (!empty($redisConf['auth'])) {
            $options["auth"] = $redisConf['auth'];
            $redisObj->auth($redisConf['auth']);
        }
        $redisObj->select($redisConf['db']);

        $client = new Client('redis://' . $redisConf['host'] . ":" . $redisConf['port'], $options);
        // 订单超时
        $client->subscribe($redisConf['queue_name'], function ($data) use ($redisObj, $db, $prefix) {
            $rollbackFlag = $this->dealStock($data, $db, $prefix);

            if ($rollbackFlag) {
                $stockKey = 'blindbox:' . $data['blindbox_id'] . ':' . $data['box_id'];
                $redisObj->incrBy($stockKey, $data['num']);
            }
        });
    }

    /**
     * 处理库存返回
     * @param $param
     * @param $db
     * @param $prefix
     * @return bool
     */
    protected function dealStock($param, $db, $prefix)
    {
        $db->beginTrans();
        try {

            $info = $db->select('pay_status')->from($prefix . 'order')->where('id=' . $param['order_id'])->row();
            if ($info['pay_status'] != 1) {
                return false;
            }

            $db->update($prefix . 'order')->cols([
                'close_time' => now(),
                'status' => 7, // 订单关闭
                'pay_status' => 7 // 支付超时
            ])->where('id=' . $param['order_id'])->query();

            $db->commitTrans();
            return true;
        } catch (\Exception $e) {
            $db->rollBackTrans();
            Log::error('处理超时未支付订单:' . formatErrMsg($e));
        }

        return false;
    }
}
