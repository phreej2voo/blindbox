<?php

namespace strategy\order\impl;

use app\model\marketing\VipCard;
use app\model\marketing\VipCardOrder;
use app\model\marketing\VipCardUser;
use strategy\order\OrderInterface;
use think\facade\Db;
use think\facade\Log;

class CardOrder implements OrderInterface
{
    public function dealOrder($param)
    {
        $data = $param['data'];

        $orderModel = new VipCardOrder();
        $orderInfo = $orderModel->findOne([
            'order_no' => $data->out_trade_no,
            'status' => 1
        ])['data'];

        if (empty($orderInfo)) {
            Log::error('【订单未查询到错误】三方支付回调异常: ' . json_encode($param));
            return dataReturn(-1);
        }

        Db::startTrans();
        try {

            // 维护订单状态
            $orderModel->where(['id' => $orderInfo['id']])->update([
                'status' => 2, // 已支付
                'third_no' => $data->transaction_id,
                'third_return' => json_encode($data->all()),
                'update_time' => now()
            ]);

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
            return dataReturn(0, '购买成功');
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('余额充值订单出现错误: ' . $data->out_trade_no . '|' . $e->getTraceAsString());
            return dataReturn(-10, '购买异常' . $e->getMessage());
        }
    }
}