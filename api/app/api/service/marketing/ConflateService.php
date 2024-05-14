<?php

namespace app\api\service\marketing;

use app\model\goods\Goods;
use app\model\marketing\Conflate;
use app\model\marketing\ConflateDetail;
use app\model\marketing\ConflateGoods;
use app\model\marketing\ConflateProgress;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use think\facade\Db;
use utils\LuaScript;
use utils\Tools;

class ConflateService
{
    /**
     * 获取合成活动列表
     * @return array
     */
    public function getList($param)
    {
        try {

            $blindboxConflateModel = new Conflate();
            // 一键设置过期活动
            $blindboxConflateModel->where('end_time', '<', now())->update([
                'status' => 2, // 下线
                'update_time' => now()
            ]);

            $list = $blindboxConflateModel->field('id,name,start_time,end_time,goods_name,image,stock,sales,price')
                ->where('status', 1)->where('is_del', 1)->paginate($param['limit']);

            $userInfo = getUserInfo();
            $conflateIds = [];
            $list->each(function ($item) use (&$conflateIds) {

                $item->start_time = date('m-d H:i', strtotime($item->start_time));
                $item->end_time = date('m-d H:i', strtotime($item->end_time));
                $conflateIds[] = $item->id;

                return $item;
            });

            $conflateProgress = (new ConflateProgress())->field('conflate_id,progress')
                ->whereIn('conflate_id', $conflateIds)->where('user_id', $userInfo['id'])->where('status', 1)->select();
            $conflate2Progress = [];
            foreach ($conflateProgress as $vo) {
                $conflate2Progress[$vo['conflate_id']] = $vo['progress'];
            }

            $list->each(function ($item) use ($conflate2Progress) {

                $item->progress = $conflate2Progress[$item->id] ?? 0;
                return $item;
            });

        } catch (\Exception $e) {
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取合成详情
     * @param $id
     * @return array
     */
    public function getDetail($id)
    {
        try {

            $userInfo = getUserInfo();

            // 合成设置
            $blindboxConflateModel = new Conflate();
            $info = $blindboxConflateModel->where('id', $id)->where('status', 1)->where('is_del', 1)->find();
            if (empty($info)) {
                throw new \Exception("获取详情错误");
            }

            // 合成进度
            $progress = (new ConflateProgress())->where('conflate_id', $id)->where('user_id', $userInfo['id'])
                ->where('status', 1)->find();

            // 获取材料图鉴
            if ($info['conflate_limit'] == 1) { // 无限制
                $material = (new Goods())->field('id as goods_id,name as goods_name,image as goods_image,conflate_val')
                    ->where('delete_flag', 1)->where('goods_type', 2)->select()->toArray();

                foreach ($material as $key => $vo) {
                    $material[$key]['goods_image'] = explode(',', $vo['goods_image'])[0];
                }
            } else { // 指定材料
                $material = (new ConflateGoods())->field('goods_id,goods_name,image as goods_image,conflate_val')
                    ->where('conflate_id', $id)->select()->toArray();
            }

            $returnList = [
                'name' => $info['name'],
                'start_time' => date('m-d H:i', strtotime($info['start_time'])),
                'end_time' => date('m-d H:i', strtotime($info['end_time'])),
                'goods_name' => $info['goods_name'],
                'image' => $info['image'],
                'stock' => $info['stock'],
                'sales' => $info['sales'],
                'price' => $info['price'],
                'conflate_val' => $info['conflate_val'],
                'progress' => empty($progress) ? 0 : $progress['progress'],
                'complete_val' => empty($progress) ? 0 : $progress['total_conflate_val'],
                'material' => $material
            ];
        } catch (\Exception $e) {
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, 'success', $returnList);
    }

    /**
     * 获取我的碎片
     * @param $id
     * @return array
     */
    public function getMyGoods($id)
    {
        try {

            $userInfo = getUserInfo();

            $blindboxConflateModel = new Conflate();
            $info = $blindboxConflateModel->field('conflate_limit')->where('id', $id)->where('status', 1)->where('is_del', 1)->find();
            if (empty($info)) {
                throw new \Exception("获取详情错误");
            }

            $where = [];
            // 限定材料
            if ($info['conflate_limit'] == 2) {
                $goodsIds = (new ConflateGoods())->where('conflate_id', $id)->column('goods_id');
                $where[] = ['goods_id', 'in', $goodsIds];
            }

            $goodsList = (new UserBoxHot())->field("goods_id,goods_name,goods_image,count('id') as num")
                ->with(['conflate'])
                ->where('user_id', $userInfo['id'])
                ->where($where)->group('goods_id')->select()->toArray();
        } catch (\Exception $e) {
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, 'success', $goodsList);
    }

    /**
     * 投放材料
     * @param $param
     * @return array
     */
    public function putIn($param)
    {
        if (empty($param['goods'])) {
            return dataReturn(-2, '请选择要投入的碎片');
        }

        $userInfo = getUserInfo();

        $luaScript = new LuaScript();
        $key = 'box_operate_' . $userInfo['id'];
        $luaScript->lock($key);

        Db::startTrans();
        try {

            $blindboxConflateModel = new Conflate();
            $info = $blindboxConflateModel->where('id', $param['conflate_id'])->where('status', 1)->where('is_del', 1)->find();
            if (empty($info)) {
                throw new \Exception("获取详情错误");
            }

            if (empty($info['conflate_val'])) {
                throw new \Exception("合成商品设置错误");
            }

            // 查询是否还有足够的库存
            if ($info['stock'] - $info['sales'] <= 0) {
                throw new \Exception("该活动已经被兑换完了");
            }

            $putGoods = [];
            foreach ($param['goods'] as $vo) {
                $putGoods[] = $vo['goods_id'];
            }

            if ($info['conflate_limit'] == 2) {
                $needGoods = (new ConflateGoods())->where('conflate_id', $param['conflate_id'])->column('goods_id');
                $error = array_diff($putGoods, $needGoods);
                if (!empty($error)) {
                    throw new \Exception('您选择的碎片有误');
                }
            }

            // 查出商品的合成值
            $goodsConflate = (new Goods())->field('id,conflate_val')->whereIn('id', $putGoods)->select()->toArray();
            $goodsId2Conflate = [];
            foreach ($goodsConflate as $vo) {
                $goodsId2Conflate[$vo['id']] = $vo['conflate_val'];
            }

            $totalConflateVal = 0; // 累计合成值
            $boxHotModel = new UserBoxHot();
            $boxLogModel = new UserBoxLog();
            $conflateDetail = new ConflateDetail();
            $detail = [];
            foreach ($param['goods'] as $vo) {
                $list = $boxHotModel->where('user_id', $userInfo['id'])->where('goods_id', $vo['goods_id'])->limit($vo['num'])->select()->toArray();
                if (count($list) != $vo['num']) {
                    throw new \Exception('您没有足够数量的碎片');
                }

                $totalConflateVal += ($goodsId2Conflate[$vo['goods_id']] * $vo['num']);

                $ids = [];
                $uuids = [];
                foreach ($list as $v) {
                    $detail[] = [
                        'progress_id' => 0,
                        'goods_id' => $v['goods_id'],
                        'goods_name' => $v['goods_name'],
                        'goods_image' => $v['goods_image'],
                        'box_data' => json_encode($v),
                        'create_time' => now()
                    ];

                    $ids[] = $v['id'];
                    $uuids[] = $v['uuid'];
                }

                $boxHotModel->whereIn('id', $ids)->delete();
                $boxLogModel->whereIn('uuid', $uuids)->update([
                    'status' => 4, // 已合成
                    'update_time' => now()
                ]);
            }

            // 写入合成进度表
            $progressModel = new ConflateProgress();
            $has = $progressModel->where('user_id', $userInfo['id'])->where('status', 1)
                ->where('conflate_id', $param['conflate_id'])->find();
            if (empty($has)) {
                $progressId = $progressModel->insertGetId([
                    'conflate_id' => $param['conflate_id'],
                    'user_id' => $userInfo['id'],
                    'username' => $userInfo['nickname'],
                    'progress' => round($totalConflateVal / $info['conflate_val'], 2),
                    'total_conflate_val' => $totalConflateVal,
                    'status' => 1,
                    'create_time' => now()
                ]);
            } else {
                $progressId = $has['id'];
                $progressModel->where('id', $has['id'])->update([
                    'progress' => round(($has['total_conflate_val'] + $totalConflateVal) / $info['conflate_val'], 2),
                    'total_conflate_val' => $has['total_conflate_val'] + $totalConflateVal,
                    'update_time' => now()
                ]);
            }

            // 补全数据
            foreach ($detail as $key => $vo) {
                $detail[$key]['progress_id'] = $progressId;
            }

            $conflateDetail->insertAll($detail);

            Db::commit();
            $luaScript->release($key);
        } catch (\Exception $e) {
            Db::rollback();
            $luaScript->release($key);
            return dataReturn(-10, $e->getMessage());
        }

        return dataReturn(0, '投入成功');
    }

    /**
     * 获取进度详情
     * @param $conflateId
     * @return array
     */
    public function getProgressDetail($conflateId)
    {
        $userInfo = getUserInfo();

        $blindboxConflateModel = new Conflate();
        $info = $blindboxConflateModel->findOne([
            'id' => $conflateId,
            'status' => 1,
            'is_del' => 1
        ])['data'];

        if (empty($info)) {
            return dataReturn(-2, '进阶数据异常');
        }

        // 查询正在进行的进阶
        $progressModel = new ConflateProgress();
        $progress = $progressModel->findOne([
            'user_id' => $userInfo['id'],
            'conflate_id' => $conflateId,
            'status' => 1
        ])['data'];
        if (empty($progress)) {
            return dataReturn(0, 'success', [
                'progress_id' => 0,
                'total_conflate' => $info['conflate_val'],
                'now_conflate' => 0,
                'goods' => []
            ]);
        }

        $goods = (new ConflateDetail())->field('goods_id,goods_name,goods_image,count(*) as num')
            ->where('progress_id', $progress['id'])->group('goods_id')->select()->toArray();

        return dataReturn(0, 'success', [
            'progress_id' => $progress['id'],
            'total_conflate' => $info['conflate_val'],
            'now_conflate' => $progress['total_conflate_val'],
            'goods' => $goods
        ]);
    }

    /**
     * 移除碎片
     * @param $param
     * @return array
     */
    public function remove($param)
    {
        if (empty($param['goods'])) {
            return dataReturn(-2, '请选择要投入的碎片');
        }

        $userInfo = getUserInfo();

        $luaScript = new LuaScript();
        $key = 'box_operate_' . $userInfo['id'];
        $luaScript->lock($key);

        Db::startTrans();
        try {

            $progressModel = new ConflateProgress();
            $info = $progressModel->where('id', $param['progress_id'])->where('user_id', $userInfo['id'])->where('status', 1)->find();
            if (empty($info)) {
                throw new \Exception('进度信息异常');
            }

            $removeGoods = [];
            foreach ($param['goods'] as $vo) {
                $removeGoods[] = $vo['goods_id'];
            }

            // 查出商品的合成值
            $goodsConflate = (new Goods())->field('id,conflate_val')->whereIn('id', $removeGoods)->select()->toArray();
            $goodsId2Conflate = [];
            foreach ($goodsConflate as $vo) {
                $goodsId2Conflate[$vo['id']] = $vo['conflate_val'];
            }

            $totalConflateVal = 0; // 累计合成值
            $boxHotModel = new UserBoxHot();
            $boxLogModel = new UserBoxLog();
            $conflateDetail = new ConflateDetail();
            foreach ($param['goods'] as $vo) {
                $list = $conflateDetail->where('goods_id', $vo['goods_id'])->where('progress_id', $param['progress_id'])
                    ->limit($vo['num'])->select()->toArray();
                if (count($list) != $vo['num']) {
                    throw new \Exception('您没有足够数量的碎片可移除');
                }

                $totalConflateVal += ($goodsId2Conflate[$vo['goods_id']] * $vo['num']);

                $ids = [];
                $boxHot = [];
                $uuids = [];
                foreach ($list as $v) {
                    $ids[] = $v['id'];
                    $boxInfo = json_decode($v['box_data'], true);
                    $boxHot[] = $boxInfo;
                    $uuids[] = $boxInfo['uuid'];
                }

                $conflateDetail->whereIn('id', $ids)->delete();
                // 放回赏袋中
                $boxHotModel->insertAll($boxHot);
                // 修改状态
                $boxLogModel->whereIn('uuid', $uuids)->update([
                    'status' => 1, // 盒子中
                    'update_time' => now()
                ]);
            }

            $blindboxConflateModel = new Conflate();
            $conflate = $blindboxConflateModel->where('id', $info['conflate_id'])->where('status', 1)->where('is_del', 1)->find();

            // 更新合成进度表
            $progressModel->where('id', $info['id'])->update([
                'progress' => round(($info['total_conflate_val'] - $totalConflateVal) / $conflate['conflate_val'], 2),
                'total_conflate_val' => $info['total_conflate_val'] - $totalConflateVal,
                'update_time' => now()
            ]);

            Db::commit();
            $luaScript->release($key);
        } catch (\Exception $e) {
            Db::rollback();
            $luaScript->release($key);
            return dataReturn(-10, $e->getMessage());
        }

        return dataReturn(0, '操作成功');
    }

    /**
     * 处理合成
     * @param $progressId
     * @return array
     */
    public function do($progressId)
    {
        $userInfo = getUserInfo();

        Db::startTrans();
        try {

            $progressModel = new ConflateProgress();
            $info = $progressModel->where('user_id', $userInfo['id'])->where('status', 1)
                ->where('id', $progressId)->lock(true)->find();
            if (empty($info)) {
                throw new \Exception('合成数据错误');
            }

            if ($info['progress'] < 1) {
                throw new \Exception('尚未满足合成条件');
            }

            // 可兑换数量
            $rewardNum = floor($info['progress']);

            // 赠送的数据
            $conflateModel = new Conflate();
            $conflateInfo = $conflateModel->where('id', $info['conflate_id'])->where('status', 1)->where('is_del', 1)->find();
            if (empty($conflateInfo)) {
                throw new \Exception('合成数据设置错误');
            }

            if ($conflateInfo['stock'] == $conflateInfo['sales']) {
                throw new \Exception('该活动奖品已经兑换完了');
            }

            if (($conflateInfo['sales'] + $rewardNum) > $conflateInfo['stock']) {
                throw new \Exception('您最多可兑换' . ($conflateInfo['stock'] - ($conflateInfo['sales'] + $rewardNum)) . '个');
            }

            // 发放奖品
            $reward = [];
            for ($i = 0; $i < $rewardNum; $i++) {
                $reward[] = [
                    'user_id' => $userInfo['id'],
                    'blindbox_id' => 0,
                    'box_id' => 0,
                    'order_id' => 0,
                    'record_detail_id' => 0,
                    'out_trade_no' => 0,
                    'tag_name' => '合成赠送',
                    'goods_id' => $conflateInfo['goods_id'],
                    'goods_image' => $conflateInfo['image'],
                    'goods_name' => $conflateInfo['goods_name'],
                    'status' => 1,
                    'uuid' => uniqid(),
                    'create_time' => now()
                ];
            }

            // 热点表
            (new UserBoxHot())->insertAll($reward);
            // 记录表
            (new UserBoxLog())->insertAll($reward);

            $conflateModel->where('id', $conflateInfo['id'])->update([
                'sales' => $conflateInfo['sales'] + $rewardNum,
                'update_time' => now()
            ]);

            $progressModel->where('id', $info['id'])->update([
                'status' => 2, // 已合成
                'update_time' => now()
            ]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, $e->getMessage());
        }

        return dataReturn(0, '合成成功');
    }

    /**
     * 兑换记录
     * @param $param
     * @return array
     */
    public function conflateLog($param)
    {
        $userInfo = getUserInfo();

        $where[] = ['user_id', '=', $userInfo['id']];
        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        $progressModel = new ConflateProgress();
        $list = $progressModel->field('id,conflate_id,status,create_time')->with(['conflate'])
            ->where($where)->order('id desc')->paginate($param['limit']);

        $detailModel = new ConflateDetail();
        $list->each(function ($item) use ($detailModel) {

            $item->detail = $detailModel->field(['goods_name', 'goods_image', 'count(*) as num'])
                ->where('progress_id', $item->id)->group('goods_id')->select()->toArray();
            return $item;
        });

        return dataReturn(0, 'success', $list);
    }
}