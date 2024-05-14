<?php

namespace app\admin\service\box;

use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxTag;

class BlindboxTagService
{
    /**
     * 获取盲盒标签列表
     * @param $param
     * @return array
     */
    public function getBlindboxTagList($param)
    {
        $where = [];
        if (!empty($param['name'])) {
            $where[] = ['name', '=', $param['name']];
        }

        if (isset($param['status']) && !empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        $blindboxTagModel = new BlindboxTag();
        $list = $blindboxTagModel->getPageList($param['limit'], $where, '*', 'sort desc');
        return pageReturn($list);
    }

    /**
     * 获取盲盒标签下拉列表
     * @return array
     */
    public function selectBlindboxTagList()
    {
        /** @var  $sysBaseArr */
        $sysBaseArr = getConfByType('sys_base');
        /** @var  $indexArr */
        $indexArr = json_decode($sysBaseArr['index_config'],1);
        /** @var  $list */
        $list = [];
        foreach ($indexArr as $key=>$value) {
            $list[] = [
                'id' => $key,
                'name' => $value
            ];
        }

        return dataReturn(0, 'success', $list);
    }

    /**
     * 添加盲盒标签
     * @param $param
     * @return array
     */
    public function addBlindboxTag($param)
    {
        if (empty($param['name'])) {
            return dataReturn(-1, '请输入标签名称');
        }

        $blindboxTagModel = new BlindboxTag();
        $has = $blindboxTagModel->checkUnique(['name' => $param['name']])['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该标签已经存在');
        }

        $param['create_time'] = now();
        return $blindboxTagModel->insertOne($param);
    }

    /**
     * 编辑盲盒标签
     * @param $param
     * @return array
     */
    public function editBlindboxTag($param)
    {
        if (empty($param['name'])) {
            return dataReturn(-1, '请输入标签名称');
        }

        $blindboxTagModel = new BlindboxTag();

        $where[] = ['name', '=', $param['name']];
        $where[] = ['id', '<>', $param['id']];
        $has = $blindboxTagModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该标签已经存在');
        }

        $param['update_time'] = now();
        return $blindboxTagModel->updateById($param, $param['id']);
    }

    /**
     * 删除标签
     * @param $id
     * @return array
     */
    public function delBlindboxTag($id)
    {
        $blindboxDetailModel = new BlindboxBoxesDetail();

        $has = $blindboxDetailModel->findOne(['tag_id' => $id])['data'];
        if (!empty($has)) {
            return dataReturn(-1, '该标签已被使用无法删除');
        }

        $blindboxTagModel = new BlindboxTag();
        return $blindboxTagModel->delById($id);
    }
}