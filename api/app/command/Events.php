<?php
namespace app\command;

use GatewayWorker\Lib\Gateway;
use Workerman\Worker;

class Events
{
    /**
     * 当客户端连接时触发
     * 如果业务不需此回调可以删除onConnect
     * @param $worker
     */
    public static function onWorkerStart($worker)
    {
        $config = config('websocket');
        // 监听一个http端口,提供api服务
        if($worker->id === 0) {
            $http = new Worker('http://0.0.0.0:' . $config['api_port']);
            $http->reusePort = true;

            // 当http客户端发来数据时触发
            $http->onMessage = function($connection, $data) {
                $message = $data->post();
                if ($message['type'] == 'send2group') {
                    Gateway::sendToGroup($message['group'], json_encode($message['data']));
                } else if ($message['type'] == 'send2one') {
                    Gateway::sendToUid($message['uid'], json_encode($message['data']));
                }

                $connection->send(json_encode(dataReturn(0)));
            };

            // 执行监听
            $http->listen();
        }
    }

    /**
     * 当客户端发来消息时触发
     * @param int $clientId 连接id
     * @param mixed $data 具体消息
     */
    public static function onMessage($clientId, $data)
    {
        $message = json_decode($data, true);
        switch ($message['cmd']) {

            case 'LOGIN':
                $_SESSION['id'] = $message['uid'];
                Gateway::bindUid($clientId, $message['uid']);
                Gateway::joinGroup($clientId, $message['group']);
                break;
            case 'ping':
                Gateway::sendToClient($clientId, json_encode(['type' => 'pong']));
                break;
        }
    }

    /**
     * 当用户断开连接时触发
     * @param int $clientId 连接id
     */
    public static function onClose($clientId)
    {

    }
}