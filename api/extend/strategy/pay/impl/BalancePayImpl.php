<?php

namespace strategy\pay\impl;

use app\api\service\order\AfterOrderService;
use app\model\marketing\UserCoupon;
use app\model\order\Order;
use app\model\user\UserBalanceChangeLog;
use strategy\lottery\LotteryProvider;
use strategy\pay\PayInterface;
use think\facade\Db;
use utils\CapitalChange;
use utils\LuaScript;

class BalancePayImpl implements PayInterface
{
    public function miniPay($param)
    {
        return $this->pay($param);
    }

    public function appPay($param)
    {
        return $this->pay($param);
    }

    public function wapPay($param)
    {
        return $this->pay($param);
    }

    public function refund($param)
    {

    }

    protected function pay($param)
    {
        $luaRedis = new LuaScript();
        $lockKey = 'lottery_bx_' . $param['blindbox_id'] . '_b_' . $param['box_id'];
        $luaRedis->lock($lockKey, 30);

        Db::startTrans();
        try {

            // 减少余额
            $userRes = CapitalChange::balanceDec([
                'amount' => $param['pay_price'],
                'user_id' => $param['user_id']
            ]);
            if ($userRes['code'] != 0) {
                return $userRes;
            }
            $userInfo = $userRes['data'];

            $param['hash_key'] = $userInfo['hash_key'];

            // 调用抽奖玩法
            $lotteryProvider = new LotteryProvider($param['play_id']);
            $res = $lotteryProvider->getStrategy()->run($param);
            if ($res['code'] != 0) {
                Db::rollback();
                return $res;
            }

            // 记录余额变动记录
            $res = (new UserBalanceChangeLog())->insertOne([
                'user_id' => $param['user_id'],
                'username' => $param['user_name'],
                'balance' => 0 - $param['pay_price'],
                'type' => 1,
                'order_id' => $param['id'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
            if ($res['code'] != 0) {
                Db::rollback();
                return $res;
            }

            // 修改订单状态
            $orderModel = new Order();
            $res = $orderModel->updateById([
                'pay_status' => 2,
                'status' => 5,
                'update_time' => date('Y-m-d H:i:s')
            ], $param['id']);
            if ($res['code'] != 0) {
                Db::rollback();
                return $res;
            }

            $orderInfo = $orderModel->where('id', $param['id'])->find();
            // 触发支付后逻辑
            (new AfterOrderService())->run($orderInfo);

            Db::commit();
            $luaRedis->release($lockKey);
        } catch (\Exception $e) {
            Db::rollback();
            $luaRedis->release($lockKey);
            return dataReturn(-12, $e->getMessage() . '---' . $e->getFile() . '--' . $e->getLine());
        }

        return dataReturn(0, '支付成功');
    }

    public function getConfig()
    {
        return [];
    }
}