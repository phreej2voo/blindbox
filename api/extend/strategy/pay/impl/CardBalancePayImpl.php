<?php

namespace strategy\pay\impl;

use app\model\marketing\VipCard;
use app\model\marketing\VipCardOrder;
use app\model\marketing\VipCardUser;
use app\model\user\UserBalanceChangeLog;
use strategy\pay\PayInterface;
use think\facade\Db;
use think\facade\Log;
use utils\CapitalChange;
use utils\Tools;

class CardBalancePayImpl implements PayInterface
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
        Db::startTrans();
        try {

            // 减少余额
            $userRes = CapitalChange::balanceDec([
                'amount' => $param['pay_price'],
                'user_id' => $param['user_id']
            ]);
            if ($userRes['code'] != 0) {
                throw new \Exception($userRes['msg']);
            }

            // 记录余额变动记录
            $res = (new UserBalanceChangeLog())->insertOne([
                'user_id' => $param['user_id'],
                'username' => $param['username'],
                'balance' => 0 - $param['pay_price'],
                'type' => 9, // 购买会员
                'order_id' => $param['order_id'],
                'create_time' => date('Y-m-d H:i:s')
            ]);
            if ($res['code'] != 0) {
                throw new \Exception($userRes['msg']);
            }

            // 修改订单状态
            $vipCardOrderModel = new VipCardOrder();
            $orderInfo = $vipCardOrderModel->where('order_no', $param['pay_order_no'])->find();

            $res = $vipCardOrderModel->updateById([
                'status' => 2,
                'update_time' => date('Y-m-d H:i:s')
            ], $param['order_id']);
            if ($res['code'] != 0) {
                throw new \Exception($userRes['msg']);
            }

            if ($orderInfo['card_type'] == 1) {
                $endDate = date('Y-m-d', strtotime('+7 days'));
            } else {
                $endDate = date('Y-m-d', strtotime('+30 days'));
            }

            // 写入用户卡
            (new VipCardUser())->insert([
                'card_no' => makeOrderNo('CA', 3),
                'order_id' => $orderInfo['id'],
                'user_id' => $orderInfo['user_id'],
                'username' => $orderInfo['username'],
                'card_id' => $orderInfo['card_id'],
                'status' => 1, // 生效中
                'start_date' => date('Y-m-d'),
                'end_date' => $endDate,
                'create_time' => now()
            ]);

            // 会员卡信息
            $vipCardModel = new VipCard();
            $cardInfo = $vipCardModel->where('id', $orderInfo['card_id'])->lock(true)->find();
            if ($cardInfo['stock'] != -1) {
                $vipCardModel->where('id', $cardInfo['id'])->dec('stock')->update();
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('订单支付错误: ' . formatErrMsg($e));
            return dataReturn(-12, $e->getMessage());
        }

        return dataReturn(0, '支付成功');
    }

    public function getConfig()
    {
        // TODO: Implement getConfig() method.
    }
}