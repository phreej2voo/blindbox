<?php

namespace strategy\play\impl;

use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxGoods;
use app\model\goods\Goods;
use app\model\order\OrderRecord;
use app\model\order\OrderRecordDetail;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use strategy\play\PlayInterface;
use utils\MyRedis;

/**
 * 一番赏
 */
class YiFan implements PlayInterface
{
    public function stock(&$awardPool, &$nowStock, $awardGoodsId)
    {
        $awardPool[$awardGoodsId] -= 1; // 减库存
        $nowStock[$awardGoodsId] -= 1;
    }

    public function updateData($param)
    {
        $data = $param['param'];
        $totalCostPrice = $param['totalCostPrice'];
        $totalAmount = $param['totalAmount'];
        $profitLossAmount = round($param['totalAmount'] - $param['totalCostPrice'], 2);

        // 维护盲盒总销量
        $blindboxModel = new Blindbox();
        $blindboxModel->where('id', $data['blindbox_id'])->inc('sales', $data['total_num'])->update();

        // 维护盲盒信息
        $blindboxBoxesModel = new BlindboxBoxes();
        $boxesInfo = $blindboxBoxesModel->where('blindbox_id', $data['blindbox_id'])->where('box_no', $data['box_id'])->find();
        $blindboxBoxesModel->where('id', $boxesInfo['id'])->update([
            'sales_cost_price' => round($boxesInfo['sales_cost_price'] + $totalCostPrice, 2),
            'sales_amount' => round($boxesInfo['sales_amount'] + $totalAmount, 2),
            'profit_loss_amount' => $boxesInfo['profit_loss_amount'] + $profitLossAmount,
            'sales' => $boxesInfo['sales'] + $data['total_num'],
            'stock' => $boxesInfo['stock'] - $data['total_num'],
            'status' => (($boxesInfo['stock'] - $data['total_num']) <= 0) ? 2 : 1,
            'update_time' => now()
        ]);

        // 维护箱子信息
        $blindboxBoxesDetailModel = new BlindboxBoxesDetail();
        foreach ($param['rewardGoods2Num'] as $key => $vo) {
            $blindboxBoxesDetailModel->where('blindbox_id', $data['blindbox_id'])->where('box_id', $data['box_id'])
                ->where('blindbox_goods_id', $key)
                ->inc('sales', $vo)
                ->dec('stock', $vo)
                ->update();
        }
    }

    public function award($param)
    {
        $data = $param['param'];
        $redis = (new MyRedis())->getObject();
        $redisKey = 'blindbox:' . $data['blindbox_id'] . ':' . $data['box_id'];
        $stock = $redis->get($redisKey);

        // 最后一抽了，赠送last赏
        if (!is_numeric($stock) || $stock > 0) {
            // 推送剩余库存
            curlPost('http://127.0.0.1:' . config('websocket.api_port'), [
                'type' => 'send2group',
                'group' => $data['blindbox_id'] . '_' . $data['box_id'],
                'data' => [
                    'type' => 'left_stock',
                    'data' => $param['nowStock']
                ]
            ]);

            return dataReturn(0);
        }

        // 确定last的商品
        $goodsList = (new BlindboxGoods())->where('blindbox_id', $data['blindbox_id'])
            ->where('tag_id', 0)->select();
        $goodsIds = [];

        $boxData = [];
        $orderRecordDetailModel = new OrderRecordDetail();
        $totalExchangeIntegral = 0;

        foreach ($goodsList as $vo) {
            // 中奖详情
            $detailId = $orderRecordDetailModel->insertGetId([
                'order_record_id' => $param['recordId'],
                'order_id' => $data['id'],
                'blindbox_id' => $data['blindbox_id'],
                'box_id' => $data['box_id'],
                'user_id' => $data['user_id'],
                'user_name' => $data['user_name'],
                'order_time' => time(),
                'tag_id' => 0, // 标签
                'tag_name' => 'LAST',
                'goods_id' => $vo['goods_id'],
                'goods_image' => $vo['goods_image'],
                'goods_name' => $vo['goods_name'],
                'unit_price' => 0, // 赠送不花钱
                'goods_price' => $vo['price'],
                'recovery_price' => $vo['recovery_price'],
                'profit' => round($vo['recovery_price'] / $param['ratio'], 2), // 换算成真实的RMB盈亏
                'ratio' => $param['ratio'],
                'status' => 1,
                'create_time' => now()
            ]);

            // 放入盒子
            $boxData[] = [
                'user_id' => $data['user_id'],
                'blindbox_id' => $data['blindbox_id'],
                'box_id' => $data['box_id'],
                'order_id' => $data['id'],
                'record_detail_id' => $detailId,
                'out_trade_no' => $data['order_no'],
                'tag_name' => 'LAST',
                'goods_id' => $vo['goods_id'],
                'goods_image' => $vo['goods_image'],
                'goods_name' => $vo['goods_name'],
                'status' => 1,
                'uuid' => uniqid(),
                'create_time' => now()
            ];

            $totalExchangeIntegral += $vo['recovery_price'];

            $goodsIds[] = $vo['goods_id'];
            $param['nowStock'][$vo['goods_id']] = 0;
        }

        // 减库存
        (new BlindboxBoxesDetail())->where('blindbox_id', $data['blindbox_id'])->where('box_id', $data['box_id'])
            ->whereIn('blindbox_goods_id', $goodsIds)->inc('sales', 1)->dec('stock', 1)->update();

        (new OrderRecord())->where('id', $param['recordId'])->inc('award_num', count($goodsList))
            ->inc('total_exchange_integral', $totalExchangeIntegral)
            ->inc('total_profit', round($totalExchangeIntegral / $param['ratio'], 2))
            ->update();

        // 盒子表
        (new UserBoxHot())->insertAll($boxData);
        // 盒子记录表
        (new UserBoxLog())->insertAll($boxData);
        // 移除redis
        $redis->del($redisKey);

        // 记录盈亏
        $goodsAmount = (new Goods())->whereIn('id', $goodsIds)->sum('cost_price');
        $blindboxBoxesModel = new BlindboxBoxes();
        $boxesInfo = $blindboxBoxesModel->where('blindbox_id', $data['blindbox_id'])->where('box_no', $data['box_id'])->find();
        $blindboxBoxesModel->where('id', $boxesInfo['id'])->update([
            'profit_loss_amount' => $boxesInfo['profit_loss_amount'] - $goodsAmount,
            'update_time' => now()
        ]);

        // 推送全部售罄
        curlPost('http://127.0.0.1:' . config('websocket.api_port'), [
            'type' => 'send2group',
            'group' => $data['blindbox_id'] . '_' . $data['box_id'],
            'data' => [
                'type' => 'left_stock',
                'data' => $param['nowStock']
            ]
        ]);

        return dataReturn(0);
    }
}