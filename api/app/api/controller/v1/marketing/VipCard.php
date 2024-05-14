<?php

namespace app\api\controller\v1\marketing;

use app\api\controller\Base;
use app\api\service\marketing\VipCardService;

class VipCard extends Base
{
    /**
     * 会员卡列表
     */
    public function cardList(VipCardService $vipCardService)
    {
        return json($vipCardService->getCardList(input('param.')));
    }

    /**
     * 创建订单
     */
    public function createOrder(VipCardService $vipCardService)
    {
        return json($vipCardService->createOrder(input('param.')));
    }

    /**
     * 我的待领取
     */
    public function equity(VipCardService $vipCardService)
    {
        return json($vipCardService->getMyEquity());
    }

    /**
     * 领取权益
     */
    public function receive(VipCardService $vipCardService)
    {
        return json($vipCardService->doReceive(input('post.')));
    }

    /**
     * 领取记录
     */
    public function log(VipCardService $vipCardService)
    {
        return json($vipCardService->getReceiveLog(input('param.')));
    }
}