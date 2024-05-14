<?php

namespace app\admin\service\box;

use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxType;

class BlindboxTypeService
{
    /**
     * 获取盲盒分类列表
     * @param $param
     * @return array
     */
    public function getBlindboxTypeList($param)
    {
        $where = [];
        if (!empty($param['name'])) {
            $where[] = ['name', '=', $param['name']];
        }

        if (isset($param['status']) && !empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        $blindboxTagModel = new BlindboxType();
        $list = $blindboxTagModel->getPageList($param['limit'], $where, '*', 'sort desc');
        return pageReturn($list);
    }

    /**
     * 获取盲盒分类列表
     * @return array
     */
    public function selectBlindboxTypeList()
    {
        /** @var  $where */
        $where = [];
        $where[] = ['status', '=', 1];

        return (new BlindboxType())->getAllList($where, '*', 'id asc');
    }

    /**
     * 添加盲盒分类
     * @param $param
     * @return array
     */
    public function addBlindboxType($param)
    {
        if (empty($param['name'])) {
            return dataReturn(-1, '请输入分类名称');
        }

        $blindboxTagModel = new BlindboxType();
        $has = $blindboxTagModel->checkUnique(['name' => $param['name']])['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该分类已经存在');
        }

        $param['create_time'] = now();
        return $blindboxTagModel->insertOne($param);
    }

    /**
     * 编辑盲盒分类
     * @param $param
     * @return array
     */
    public function editBlindboxType($param)
    {
        if (empty($param['name'])) {
            return dataReturn(-1, '请输入标签名称');
        }

        $blindboxTagModel = new BlindboxType();

        $where[] = ['name', '=', $param['name']];
        $where[] = ['id', '<>', $param['id']];
        $has = $blindboxTagModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该分类已经存在');
        }

        $param['update_time'] = now();
        return $blindboxTagModel->updateById($param, $param['id']);
    }

    /**
     * 删除分类
     * @param $id
     * @return array
     */
    public function delBlindboxType($id)
    {
        $blindboxDetailModel = new BlindboxBoxesDetail();

        $has = $blindboxDetailModel->findOne(['tag_id' => $id])['data'];
        if (!empty($has)) {
            return dataReturn(-1, '该标签已被使用无法删除');
        }

        $blindboxTagModel = new BlindboxType();
        return $blindboxTagModel->delById($id);
    }
}
