<?php

namespace app\api\controller\v1\goods;

use app\api\service\goods\BlindboxService;
use app\BaseController;

class Blindbox extends BaseController
{
    /**
     * 盲盒商品详情
     */
    public function detail(BlindboxService $blindboxService)
    {
        return json($blindboxService->getBlindboxDetail(input('param.')));
    }

    /**
     * 盲盒商品的介绍
     */
    public function info(BlindboxService $blindboxService)
    {
        return json($blindboxService->getGoodsInfo(input('param.')));
    }

    /**
     * 换箱信息
     */
    public function boxes(BlindboxService $blindboxService)
    {
        return json($blindboxService->getBoxes(input('post.')));
    }

    /**
     * 中奖记录
     */
    public function reward(BlindboxService $blindboxService)
    {
        return json($blindboxService->getRewardList(input('param.')));
    }
}