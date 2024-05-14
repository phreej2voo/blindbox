<?php
declare (strict_types=1);

namespace app\command;

use GatewayWorker\BusinessWorker;
use GatewayWorker\Gateway;
use GatewayWorker\Register;
use think\console\Command;
use think\console\Input;
use think\console\input\Argument;
use think\console\input\Option;
use think\console\Output;
use Workerman\Worker;

class Websocket extends Command
{
    // 修改命令文件
    protected function configure()
    {
        // 指令配置
        $this->setName('websocket')
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

        $config = config('websocket');

        // 业务进程
        $worker = new BusinessWorker();
        $worker::$pidFile = rtrim(root_path(), '/') . '/runtime/yfs_bs.pid';
        $worker->name = 'wsBsWorker';
        $worker->count = $config['business_count'];
        $worker->eventHandler = Events::class;
        $worker->registerAddress = '127.0.0.1:' . $config['register_port'];

        // ssl的上下文配置
        $context = [];
        if ($config['ssl_open']) {
            $context = $config['context'];
            if ($config['self_cert']) {
                $context['allow_self_signed'] = true;
            }

            $context = [
                'ssl' => $context
            ];
        }

        // 链接进程
        $gateway = new Gateway("websocket://0.0.0.0:" . $config['ws_port'], $context);
        $gateway->name = 'wsGateway';
        $worker::$pidFile = rtrim(root_path(), '/') . '/runtime/yfs_gy.pid';
        $gateway->count = $config['gateway_count'];
        $gateway->lanIp = '127.0.0.1';
        $gateway->startPort = 2900;
        if ($config['ssl_open']) {
            $gateway->transport = 'ssl';
        }
        $gateway->registerAddress = '127.0.0.1:' . $config['register_port'];

        // 注册中心
        new Register('text://127.0.0.1:' . $config['register_port']);

        // 启动
        Worker::runAll();
    }
}
