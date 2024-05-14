<?php

namespace app\admin\service\box;

use app\admin\validate\BlindboxValidate;
use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxGoods;
use think\exception\ValidateException;
use think\facade\Db;

class BlindboxService
{
    /**
     * 获取盲盒列表
     * @param $param
     * @return array
     */
    public function getBlindboxList($param)
    {
        $where[] = ['is_delete', '=', 1];
        if (!empty($param['name'])) {
            $where[] = ['name', '=', $param['name']];
        }

        if (!empty($param['play_id'])) {
            $where[] = ['play_id', '=', $param['play_id']];
        }

        try {

            $blindboxModel = new Blindbox();
            $list = $blindboxModel->where($where)->order('sort desc')->paginate($param['limit']);

            $blindboxBoxesModel = new BlindboxBoxes();
            $blindboxGoodsModel = new BlindboxGoods();
            $list->each(function ($item) use ($blindboxBoxesModel, $blindboxGoodsModel) {
                $item->box_num = $blindboxBoxesModel->where('blindbox_id', $item->id)->count('id');
                $item->goods_num = $blindboxGoodsModel->where('blindbox_id', $item->id)->count('id');
            });
        } catch (\Exception $e) {
            return dataReturn(-5, $e->getMessage());
        }

        return pageReturn(dataReturn(0, 'success', $list));
    }

    /**
     * 添加盲盒
     * @param $param
     * @return array
     */
    public function addBlindbox($param)
    {
        try {
            validate(BlindboxValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        if ($param['price'] < 0) {
            return dataReturn(-3, '单抽价格必须大于等于0');
        }

        $blindboxModel = new Blindbox();
        $has = $blindboxModel->checkUnique(['name' => $param['name'], 'is_delete' => 1])['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该盲盒已经存在');
        }

        $param['create_time'] = now();
        return $blindboxModel->insertOne($param);
    }

    /**
     * 编辑盲盒
     * @param $param
     * @return array
     */
    public function editBlindbox($param)
    {
        try {
            validate(BlindboxValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        if ($param['price'] < 0) {
            return dataReturn(-3, '单抽价格必须大于等于0');
        }

        $blindboxModel = new Blindbox();

        $where[] = ['name', '=', $param['name']];
        $where[] = ['id', '<>', $param['id']];
        $where[] = ['is_delete', '=', 1];
        $has = $blindboxModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-2, '该盲盒已经存在');
        }

        $param['update_time'] = now();
        return $blindboxModel->updateById($param, $param['id']);
    }

    /**
     * 删除盲盒
     * @param $id
     * @return array
     */
    public function delBlindbox($id)
    {
        Db::startTrans();
        try {

            // 删除盲盒
            (new Blindbox())->where('id', $id)->update([
                'is_delete' => 2,
                'update_time' => now()
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, '删除失败');
        }

        return dataReturn(0, '删除成功');
    }
}