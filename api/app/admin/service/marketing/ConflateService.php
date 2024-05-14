<?php
namespace app\admin\service\marketing;

use app\admin\validate\ConflateValidate;
use app\model\marketing\Conflate;
use app\model\marketing\ConflateGoods;
use app\model\marketing\ConflateProgress;
use think\exception\ValidateException;
use think\facade\Db;

class ConflateService
{
    /**
     * 获取合成活动列表
     * @param $param
     * @return array
     */
    public function getConflateList($param)
    {
        $where[] = ['is_del', '=', 1];
        if (!empty($param['name'])) {
            $where[] = ['name', 'like', '%' . $param['name'] . '%'];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        if (!empty($param['activity_time'])) {
            $where[] = ['start_time', '>=', $param['activity_time'][0] . ' 00:00:00'];
            $where[] = ['end_time', '<=', $param['activity_time'][1] . ' 23:59:59'];
        }

        $list = (new Conflate())->where($where)->with(['goods'])->order('sort desc')->paginate($param['limit']);
        return dataReturn(0, 'success', $list);
    }

    /**
     * 添加合成活动
     * @param $param
     * @return array
     */
    public function addConflate($param)
    {
        try {
            validate(ConflateValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-2, $e->getError());
        }

        if ($param['conflate_limit'] == 2 && empty($param['conflate_goods'])) {
            return dataReturn(-3, '限定材料不能为空');
        }

        Db::startTrans();
        try {

            $conflateId = (new Conflate())->insertGetId([
                'name' => $param['name'],
                'start_time' => $param['activity_time'][0],
                'end_time' => $param['activity_time'][1],
                'goods_id' => $param['goods_id'],
                'goods_name' => $param['goods_name'],
                'image' => $param['image'],
                'price' => $param['price'],
                'conflate_val' => $param['conflate_val'],
                'conflate_limit' => $param['conflate_limit'],
                'stock' => $param['stock'],
                'sort' => $param['sort'],
                'status' => $param['status'],
                'create_time' => now()
            ]);

            if ($param['conflate_limit'] == 2) {

                $goods = json_decode($param['conflate_goods'], true);
                $goodsParam = [];
                foreach ($goods as $key => $vo) {
                    $vo['conflate_id'] = $conflateId;
                    $vo['create_time'] = now();
                    $goodsParam[$key] = $vo;
                }

                (new ConflateGoods())->insertAll($goodsParam);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '添加成功');
    }

    /**
     * 编辑合成活动
     * @param $param
     * @return array
     */
    public function editConflate($param)
    {
        try {
            validate(ConflateValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-2, $e->getError());
        }

        if ($param['conflate_limit'] == 2 && empty($param['conflate_goods'])) {
            return dataReturn(-3, '限定材料不能为空');
        }

        Db::startTrans();
        try {

            (new Conflate())->where('id', $param['id'])->update([
                'name' => $param['name'],
                'start_time' => $param['activity_time'][0],
                'end_time' => $param['activity_time'][1],
                'goods_id' => $param['goods_id'],
                'goods_name' => $param['goods_name'],
                'image' => $param['image'],
                'price' => $param['price'],
                'conflate_val' => $param['conflate_val'],
                'conflate_limit' => $param['conflate_limit'],
                'stock' => $param['stock'],
                'sort' => $param['sort'],
                'status' => $param['status'],
                'update_time' => now()
            ]);

            $blindboxConflateGoodsModel = new ConflateGoods();
            // 删除旧的
            $blindboxConflateGoodsModel->where('conflate_id', $param['id'])->delete();

            if ($param['conflate_limit'] == 2) {

                $goods = json_decode($param['conflate_goods'], true);
                $goodsParam = [];
                foreach ($goods as $key => $vo) {
                    $vo['conflate_id'] = $param['id'];
                    $vo['create_time'] = now();
                    $goodsParam[$key] = $vo;
                }

                $blindboxConflateGoodsModel->insertAll($goodsParam);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '编辑成功');
    }

    /**
     * 删除合成活动
     * @param $id
     * @return array
     */
    public function delConflate($id)
    {
        return (new Conflate())->updateById([
            'is_del' => 2,
            'update_time' => now()
        ], $id);
    }

    /**
     * 获取合成记录
     * @param $param
     * @return array
     */
    public function getConflateLog($param)
    {
        $where[] = ['conflate_id', '=', $param['conflate_id']];
        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        return (new ConflateProgress())->getPageList($param['limit'], $where);
    }
}