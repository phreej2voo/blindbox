<?php

namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\AdvertisementService;

class Advertisement extends Base
{
    /**
     * 首页广告列表
     */
    public function index(AdvertisementService $advertisementService)
    {
        return json($advertisementService->getADList(input('param.')));
    }

    /**
     * 添加首页广告
     */
    public function add(AdvertisementService $advertisementService)
    {
        return json($advertisementService->addAD(input('post.')));
    }

    /**
     * 编辑广告
     */
    public function edit(AdvertisementService $advertisementService)
    {
        return json($advertisementService->editAD(input('post.')));
    }

    /**
     * 删除广告
     */
    public function del(AdvertisementService $advertisementService)
    {
        return json($advertisementService->delAD(input('param.id')));
    }
}