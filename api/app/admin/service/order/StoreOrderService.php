<?php

namespace app\admin\service\order;

use app\model\store\StoreOrder;

class StoreOrderService
{
    protected $storeOrderModel;

    public function __construct()
    {
        $this->storeOrderModel = new StoreOrder();
    }

    /**
     * 线下预约订单列表
     * @param $param
     * @return array
     */
    public function getStoreOrderList($param)
    {
        /** @var  $where */
        $where = [];

        if (!empty($param['name'])) {
            $where[] = ['name', '=', $param['name']];
        }
        /** @var  $list */
        $list = $this->storeOrderModel->getPageList($param['limit'], $where, '*', 'id desc');

        return pageReturn($list);
    }

    /**
     * 编辑线下预约订单
     * @param $param
     * @return array
     */
    public function editStoreOrder($param)
    {
        if (empty($param['uuid'])) {
            return dataReturn(-1, '券码标识');
        }

        $where[] = ['uuid', '=', $param['uuid']];
        $where[] = ['id', '<>', $param['id']];
        /** @var  $has */
        $has = $this->storeOrderModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该预约已经存在');
        }

        $param['update_time'] = now();
        return $this->storeOrderModel->updateById($param, $param['id']);
    }

    /**
     * 核销线下预约订单
     * @param $id
     * @return array
     */
    public function checkStoreOrder($id)
    {
        /** @var  $storeOrdre */
        $storeOrder = $this->storeOrderModel->findOne(['id' => $id])['data'];
        if (empty($storeOrder)) {
            return dataReturn(-1, '该预约不存在');
        }
        if ($storeOrder['status'] != 1) {
            return dataReturn(-1, '该预约状态异常');
        }

        return $this->storeOrderModel->updateById(['status' => 2, 'update_time' => now()], $id);
    }
}
