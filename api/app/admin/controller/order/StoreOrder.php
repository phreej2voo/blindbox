<?php

namespace app\admin\controller\order;

use app\admin\controller\Base;
use app\admin\service\order\StoreOrderService;

class StoreOrder extends Base
{
    /**
     * @var $storeOrderService
     */
    protected $storeOrderService;

    public function initialize()
    {
        parent::initialize();
        $this->storeOrderService = new StoreOrderService();
    }

    /**
     * 线下预约订单列表
     */
    public function index()
    {
        return json($this->storeOrderService->getStoreOrderList(input('param.')));
    }

    /**
     * 编辑线下预约订单
     */
    public function edit()
    {
        return json($this->storeOrderService->editStoreOrder(input('post.')));
    }

    /**
     * 核销线下预约订单
     */
    public function check()
    {
        return json($this->storeOrderService->checkStoreOrder(input('param.id')));
    }
}
