<?php

namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\VipCardService;

class VipCard extends Base
{
    /**
     * 会员卡列表
     */
    public function index(VipCardService $vipCardService)
    {
        return json($vipCardService->getCardList(input('param.')));
    }

    /**
     * 添加会员卡
     */
    public function add(VipCardService $vipCardService)
    {
        return json($vipCardService->addCard(input('post.')));
    }

    /**
     * 编辑会员卡
     */
    public function edit(VipCardService $vipCardService)
    {
        if (request()->isPost()) {
            return json($vipCardService->editCard(input('post.')));
        }

        return $vipCardService->getInfo(input('param.id'));
    }

    /**
     * 作废会员卡
     */
    public function del(VipCardService $vipCardService)
    {
        return json($vipCardService->delCard(input('param.id')));
    }

    /**
     * 获取够买日志
     */
    public function buy(VipCardService $vipCardService)
    {
        return json($vipCardService->getBuyLog(input('param.')));
    }

    /**
     * 领取日志
     */
    public function log(VipCardService $vipCardService)
    {
        return json($vipCardService->getReceiveLog(input('param.')));
    }
}