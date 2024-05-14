<?php

namespace app\admin\controller\store;

use app\admin\controller\Base;
use app\admin\service\store\StoreService;

class Store extends Base
{
    /**
     * @var $storeService
     */
    protected $storeService;

    public function initialize()
    {
        parent::initialize();
        $this->storeService = new StoreService();
    }


    /**
     * 门店配置列表
     * @return \think\response\Json
     */
    public function index()
    {
        return json($this->storeService->getStoreList(input('param.')));
    }


    public function add()
    {
        $store = new \app\model\store\Store();
        $param = input('post.');
        if (empty($param['address'])) {
            return json(dataReturn(-1, '请输入地址'));
        }
        if (empty($param['name'])) {
            return json(dataReturn(-1, '请输入店名'));
        }
        $param['create_time'] = now();
        return json($store->insertOne($param));
    }

    public function edit()
    {
        $blindboxTagService = new \app\model\store\Store();
        $param = input('post.');
        $param['update_time'] = now();

        return json($blindboxTagService->updateById($param,$param['id']));
    }


    public function del()
    {
        $blindboxTagService = new \app\model\store\Store();

        return json($blindboxTagService->delById(input('param.id')));
    }

}
