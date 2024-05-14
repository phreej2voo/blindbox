<?php

namespace strategy\lottery\impl;

use app\api\service\goods\BlindboxService;
use app\model\box\BlindboxBoxesConfig;
use app\model\goods\Goods;
use app\model\order\OrderRecord;
use app\model\order\OrderRecordDetail;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use strategy\lottery\LotteryInterface;
use strategy\play\PlayProgress;
use strategy\play\PlayProvider;
use utils\Tools;

class ForLotteryImpl implements LotteryInterface
{
    protected $playId = 0;

    public function __construct(int $playId)
    {
        $this->playId = $playId;
    }

    public function run($param)
    {
        // 需要查询赏种
        $blindboxBoxesConfig = new BlindboxBoxesConfig();
        // 赏种分类的具体信息
        $blindboxBoxesConfigArr = $blindboxBoxesConfig->where(['blindbox_id'=>$param['blindbox_id']])->select()->toArray();
        // 中奖订单信息
        $recordModel = new OrderRecord();
        $rewardTag = for_draw($blindboxBoxesConfigArr,$param['total_num']);
        $tmpArr = [];
        foreach (array_count_values($rewardTag) as $k=>$v) {
            $tmpArr[] = [
                'play_id'=>$param['play_id'],
                'order_id'=>$param['id'],
                'blindbox_id'=>$param['blindbox_id'],
                'box_id'=>$param['box_id'],
                'user_id'=>$param['user_id'],
                'tag_id'=>$k,
                'username'=>$param['user_name'],
                'award_num'=>$v,
                'create_time'=>now(),
            ];
        }
        $recordModel->insertAll($tmpArr);
        $playObj = (new PlayProvider($this->playId))->getStrategy();

        // 执行玩法特殊流程
        $playProgress = (new PlayProgress($playObj));
        $playProgress->run([
//            'recordId' => $recordId, // 中奖记录id
            'param' => $param,
//            'totalCostPrice' => $totalCostPrice, // 本次总成本
//            'totalAmount' => $totalAmount, // 本次总花费
//            'totalExchangeIntegral' => $totalExchangeIntegral, // 本次可回收积分价格
//            'ratio' => $ratio, // 积分换RMB比例
//            'rewardGoods2Num' => $rewardGoods2Num, // 中奖的商品
//            'nowStock' => $nowStock
        ]);

        return dataReturn(0);
    }






}
