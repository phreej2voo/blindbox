<?php

namespace app\admin\service\marketing;

use app\admin\validate\HomeADValidate;
use app\model\marketing\HomeAd;
use think\exception\ValidateException;

class AdvertisementService
{
    /**
     * 获取广告列表
     * @param $param
     * @return array
     */
    public function getADList($param)
    {
        $adModel = new HomeAd();
        return $adModel->getPageList($param['limit']);
    }

    /**
     * 添加广告
     * @param $param
     * @return array
     */
    public function addAD($param)
    {
        try {
            validate(HomeADValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-5, $e->getError());
        }

        $param['create_time'] = now();
        return (new HomeAd())->insertOne($param);
    }

    /**
     * 编辑广告
     * @param $param
     * @return array
     */
    public function editAD($param)
    {
        try {
            validate(HomeADValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-5, $e->getError());
        }

        $param['update_time'] = now();
        return (new HomeAd())->updateById($param, $param['id']);
    }

    /**
     * 删除广告
     * @param $id
     * @return array
     */
    public function delAD($id)
    {
        return (new HomeAd())->delById($id);
    }
}