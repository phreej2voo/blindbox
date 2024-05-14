<?php

namespace app\api\controller\v1\order;

use app\api\controller\Base;
use app\api\service\order\BagService;

class Bag extends Base
{
    /**
     * 仓库-盲盒列表
     */
    public function getBagBoxList(BagService $bagService)
    {
        return json($bagService->getBagBoxList(input('param.')));
    }

    /**
     * 仓库-盲盒商品详情
     */
    public function getBagBoxDetail(BagService $bagService)
    {
        return json($bagService->getBagBoxDetail(input('param.')));
    }

    /**
     * 仓库-盲盒兑换
     */
    public function bagBoxExchange(BagService $bagService)
    {
        return json($bagService->bagBoxExchange(input('post.')));
    }

    /**
     * 盲盒发货试算
     */
    public function boxTrail(BagService $bagService)
    {
        return json($bagService->bagBoxTrail(input('post.')));
    }

    /**
     * 盲盒发货邮费订单
     */
    public function boxDeliver(BagService $bagService)
    {
        return json($bagService->boxDeliver(input('post.')));
    }

    /**
     * 仓库-盲盒提货确认
     */
    public function confirmBoxDeliver(BagService $bagService)
    {
        return json($bagService->confirmBoxDeliver(input('post.')));
    }

    /**
     * 仓库-普通商品列表
     */
    public function getBagGoodsList(BagService $bagService)
    {
        return json($bagService->getBagGoodsList(input('param.')));
    }

    /**
     * 仓库-普通商品详情
     */
    public function getBagGoodsDetail(BagService $bagService)
    {
        return json($bagService->getBagGoodsDetail(input('param.')));
    }

    /**
     * 仓库-普通商品确认发货
     */
    public function bagGoodsConfirm(BagService $bagService)
    {
        return json($bagService->bagGoodsConfirm(input('post.')));
    }
}