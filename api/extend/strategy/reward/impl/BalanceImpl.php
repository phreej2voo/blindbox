<?php

namespace strategy\reward\impl;

use app\model\user\User;
use app\model\user\UserBalanceChangeLog;
use strategy\reward\RewardInterface;
use think\facade\Db;

class BalanceImpl implements RewardInterface
{
    public function getStatus($code, $userId, $status = 5)
    {
        $userBalanceChangeModel = new UserBalanceChangeLog();
        $balanceInfo = $userBalanceChangeModel->findOne([
            'code' => $code,
            'user_id' => $userId,
            'type' => $status
        ], 'id')['data'];

        return empty($balanceInfo) ? 2 : 3;
    }

    public function receive($reward, $param, $source = 5)
    {
        Db::startTrans();
        try {

            $code = makeTaskCode($param['groupId'], $param['rewardId']);
            $userBalanceChangeLogModel = new UserBalanceChangeLog();
            // 检测是否领取过
            $hasReceived = $userBalanceChangeLogModel->where('code', $code)->where('user_id', $param['user_id'])->find();
            if (!empty($hasReceived)) {
                throw new \Exception('您已领取改奖励，请勿重复领取');
            }

            $userModel = new User();
            $userModel->where('id', $param['user_id'])->lock(true)->find();

            // 写入余额变动日志
            $userBalanceChangeLogModel->insert([
                'code' => $code,
                'user_id' => $param['user_id'],
                'username' => $param['username'],
                'balance' => $reward['value'],
                'type' => $source,
                'create_time' => now()
            ]);

            // 更改余额
            $userModel->where('id', $param['user_id'])->inc('balance', $reward['value'])->update();

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, $e->getMessage() ?? '领取失败');
        }

        return dataReturn(0, '领取成功');
    }

    public function getDetail($id)
    {
        // TODO: Implement getDetail() method.
    }
}