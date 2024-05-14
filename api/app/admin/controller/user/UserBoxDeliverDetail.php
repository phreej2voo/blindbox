<?php

namespace app\admin\controller\user;

use app\admin\controller\Base;
use app\admin\service\user\UserBoxDeliverDetailService;

class UserBoxDeliverDetail extends Base
{
    /**
     * 发货详情列表
     */
    public function index()
    {
        $userBoxDeliverService = new UserBoxDeliverDetailService();
        return json($userBoxDeliverService->getUserBoxDeliverDetailList(input('param.')));
    }

    /**
     * 商品详情
     */
    public function getDetail()
    {
        $userBoxDeliverService = new UserBoxDeliverDetailService();
        return json($userBoxDeliverService->getDetail(input('param.')));
    }
}