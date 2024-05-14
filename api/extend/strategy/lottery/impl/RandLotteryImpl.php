<?php

namespace strategy\lottery\impl;

use app\api\service\goods\BlindboxService;
use app\model\goods\Goods;
use app\model\order\OrderRecord;
use app\model\order\OrderRecordDetail;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use strategy\lottery\LotteryInterface;
use strategy\play\PlayProgress;
use strategy\play\PlayProvider;
use utils\Tools;

class RandLotteryImpl implements LotteryInterface
{
    protected $playId = 0;

    public function __construct(int $playId)
    {
        $this->playId = $playId;
    }

    public function run($param)
    {
        // 盲盒信息
        $blindboxService = new BlindboxService();
        $goodsList = $blindboxService->getBlindboxBoxesDetail([
            'id' => $param['blindbox_id'],
            'box_id' => $param['box_id']
        ]);
        if (empty($goodsList['data'])) {
            return dataReturn(-11, '盲盒数据错误');
        }
        $goodsList = $goodsList['data'];

        $recordModel = new OrderRecord();
        $recordId = $recordModel->insertOne([
            'order_id' => $param['id'],
            'blindbox_id' => $param['blindbox_id'],
            'box_id' => $param['box_id'],
            'user_id' => $param['user_id'],
            'username' => $param['user_name'],
            'award_num' => 0, // 后面更新
            'total_amount' => 0, // 后面更新
            'total_exchange_integral' => 0, // 后面更新
            'total_profit' => 0, // 后面更新
            'create_time' => now()
        ]);
        if ($recordId['code'] != 0) {
            return $recordId;
        }
        $recordId = $recordId['data'];

        $awardNum = 0;
        $totalAmount = 0;
        $totalExchangeIntegral = 0;
        $totalCostPrice = 0;
        $boxData = [];
        $orderRecordDetailModel = new OrderRecordDetail();

        // 当前兑换比例
        $ratio = getConfByType('sys_base')['change_ratio'];
        if (empty($ratio)) {
            $ratio = 1; // 未设置则兑换比例为 1:1
        }

        // 奖品池子
        $awardPool = [];
        $goodsId2Map = [];
        $nowStock = [];
        foreach ($goodsList as $vo) {
            // 实时库存
            $nowStock[$vo['goods_id']] = $vo['stock'];

            // 剔除特殊的奖品
            if ($vo['tag_id'] <= 0) {
                continue;
            }

            $goodsId2Map[$vo['goods_id']] = $vo;
            $awardPool[$vo['goods_id']] = $vo['stock'];
        }

        // 奖池按库存降序
        arsort($awardPool);

        $goodsModel = new Goods();
        $rewardGoods2Num = [];
        $playObj = (new PlayProvider($this->playId))->getStrategy();

        for ($i = 0; $i < $param['total_num']; $i++) {

            $totalAmount += $param['unit_price']; // 商品单抽价格

            $awardGoodsId = $this->lottery($awardPool);
            // 执行每种玩法不同的库存策略
            $playObj->stock($awardPool, $nowStock, $awardGoodsId);

            $goods = $goodsId2Map[$awardGoodsId];
            $goods['goods_image'] = explode(',', $goods['goods_image'])[0];
            $awardNum += 1;
            $totalExchangeIntegral += $goods['recovery_price']; // 兑换的哈希币金额
            $goodsInfo = $goodsModel->where('id', $awardGoodsId)->find();
            $totalCostPrice += $goodsInfo['cost_price'];

            if (!isset($rewardGoods2Num[$goods['goods_id']])) {
                $rewardGoods2Num[$goods['goods_id']] = 1;
            } else {
                $rewardGoods2Num[$goods['goods_id']] += 1;
            }
            // 中奖详情
            $detailId = $orderRecordDetailModel->insertOne([
                'order_record_id' => $recordId,
                'order_id' => $param['id'],
                'blindbox_id' => $param['blindbox_id'],
                'box_id' => $param['box_id'],
                'user_id' => $param['user_id'],
                'user_name' => $param['user_name'],
                'user_avatar' => $param['user_avatar'],
                'order_time' => time(),
                'tag_id' => $goods['tag_id'], // 标签
                'tag_name' => Tools::getTagName($goods['tag_id'])['data'],
                'goods_id' => $goods['goods_id'],
                'goods_image' => $goods['goods_image'],
                'goods_name' => $goods['goods_name'],
                'unit_price' => $param['unit_price'],
                'goods_price' => $goods['price'],
                'recovery_price' => $goods['recovery_price'],
                'profit' => round($goods['recovery_price'] / $ratio, 2) - $param['unit_price'], // 换算成真实的RMB盈亏
                'ratio' => $ratio,
                'status' => 1,
                'create_time' => now()
            ]);
            if ($detailId['code'] != 0) {
                return $detailId;
            }
            $detailId = $detailId['data'];

            // 放入盒子
            $boxData[] = [
                'user_id' => $param['user_id'],
                'blindbox_id' => $param['blindbox_id'],
                'box_id' => $param['box_id'],
                'order_id' => $param['id'],
                'record_detail_id' => $detailId,
                'out_trade_no' => $param['order_no'],
                'tag_name' => Tools::getTagName($goods['tag_id'])['data'],
                'goods_id' => $goods['goods_id'],
                'goods_image' => $goods['goods_image'],
                'goods_name' => $goods['goods_name'],
                'status' => 1,
                'uuid' => uniqid(),
                'create_time' => now()
            ];
        }

        $res = $recordModel->updateById([
            'award_num' => $awardNum,
            'total_amount' => round($totalAmount, 2),
            'total_exchange_integral' => $totalExchangeIntegral,
            'total_profit' => round($totalExchangeIntegral / $ratio - $totalAmount, 2),
            'update_time' => now()
        ], $recordId);
        if ($res['code'] != 0) {
            return $res;
        }

        // 盒子表
        $res = (new UserBoxHot())->insertBatch($boxData);
        if ($res['code'] != 0) {
            return $res;
        }

        // 盒子记录表
        $res = (new UserBoxLog())->insertBatch($boxData);
        if ($res['code'] != 0) {
            return $res;
        }

        // 执行玩法特殊流程
        $playProgress = (new PlayProgress($playObj));
        $playProgress->run([
            'recordId' => $recordId, // 中奖记录id
            'param' => $param,
            'totalCostPrice' => $totalCostPrice, // 本次总成本
            'totalAmount' => $totalAmount, // 本次总花费
            'totalExchangeIntegral' => $totalExchangeIntegral, // 本次可回收积分价格
            'ratio' => $ratio, // 积分换RMB比例
            'rewardGoods2Num' => $rewardGoods2Num, // 中奖的商品
            'nowStock' => $nowStock
        ]);

        return dataReturn(0, 'success', ['record_id' => $recordId]);
    }

    public function lottery($arr)
    {
        $max = array_sum($arr);
        foreach ($arr as $key => $val) {
            if ($val > 0) {
                $rand_number = $this->randomNum(1, $max);
                if ($rand_number <= $val) {
                    return $key;
                } else {
                    $max -= $val;
                }
            }
        }
    }

    public function randomNum(int $min, int $max): int
    {
        return mt_rand() % ($max - $min + 1) + $min;
    }
}
