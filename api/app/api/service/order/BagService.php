<?php

namespace app\api\service\order;

use app\model\goods\Goods;
use app\model\order\Order;
use app\model\order\OrderDetail;
use app\model\order\OrderExpress;
use app\model\order\OrderRecordDetail;
use app\model\user\UserAddress;
use app\model\user\UserBoxDeliver;
use app\model\user\UserBoxDeliverDetail;
use app\model\user\UserBoxExchange;
use app\model\user\UserBoxExchangeDetail;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use app\model\user\UserIntegralChangeLog;
use strategy\pay\PayProvider;
use think\Exception;
use think\facade\Db;
use think\facade\Log;
use utils\CapitalChange;
use utils\LuaScript;
use utils\Tools;

class BagService
{
    /**
     * 仓库-盲盒列表
     * @param $param
     * @return array
     */
    public function getBagBoxList($param)
    {
        if (!checkOpen()) {
            return dataReturn(404, '站点正在维护');
        }

        try {

            $userInfo = getUserInfo();
            // 未申请的
            if ($param['type'] == 1) {

                $userBoxList = (new UserBoxHot())->with(['blindbox'])->field('blindbox_id,count(*) as `t_total`,create_time')
                    ->where('user_id', $userInfo['id'])
                    ->order('id desc')
                    ->group('blindbox_id')->paginate($param['limit']);

                // 补全特殊图片
                $userBoxList->each(function ($item) {
                    if ($item->blindbox_id == 0) {
                        $item->pic = request()->domain() . '/static/images/conflate.png';
                    }

                    return $item;
                });
            } else if ($param['type'] == 2) { // 已发货的

                $userBoxList = (new UserBoxDeliver())->field('id,deliver_no,status,create_time')->where('user_id', $userInfo['id'])
                    ->order('id desc')->paginate($param['limit']);

                $detailModel = new UserBoxDeliverDetail();
                $recordModel = new OrderRecordDetail();
                $userBoxLogModel = new UserBoxLog();
                // 发货数量
                $userBoxList->each(function ($item) use ($detailModel, $recordModel, $userBoxLogModel) {

                    $num = $detailModel->where('deliver_id', $item->id)->count('id');
                    $info = $detailModel->field('user_box_uuid,record_detail_id')->where('deliver_id', $item->id)->find();
                    // 合成的商品兑换，此处值为0
                    if ($info['record_detail_id'] == 0) {
                        $goodsInfo = $userBoxLogModel->field('goods_name,goods_image')->where('uuid', $info['user_box_uuid'])->find();
                    } else {
                        $goodsInfo = $recordModel->field('goods_name,goods_image')->where('id', $info['record_detail_id'])->find();
                    }

                    $item->title = ($num == 1) ? $goodsInfo['goods_name'] : $goodsInfo['goods_name'] . '等';
                    $item->pic = $goodsInfo['goods_image'];
                    $item->num = $num;

                    return $item;
                });
            } else { // 已兑换

                $userBoxList = (new UserBoxExchange())->field('id,exchange_no,exchange_num,total_amount,status,create_time')
                    ->where('user_id', $userInfo['id'])
                    ->order('id desc')->paginate($param['limit']);

                $detailModel = new UserBoxExchangeDetail();
                $recordModel = new OrderRecordDetail();
                $goodsModel = new Goods();
                $userBoxList->each(function ($item) use ($detailModel, $recordModel, $goodsModel) {

                    $info = $detailModel->field('goods_id,record_detail_id')->where('exchange_id', $item->id)->find();
                    // 合成的商品兑换，此处值为0
                    if ($info['record_detail_id'] == 0) {
                        $goodsInfo = $goodsModel->field('name as goods_name,image as goods_image')->where('id', $info['goods_id'])->find();
                        $goodsInfo['goods_image'] = explode(',', $goodsInfo['goods_image'])[0];
                    } else {
                        $goodsInfo = $recordModel->field('goods_name,goods_image')->where('id', $info['record_detail_id'])->find();
                    }

                    $item->title = ($item->exchange_num == 1) ? $goodsInfo['goods_name'] : $goodsInfo['goods_name'] . '等';
                    $item->pic = $goodsInfo['goods_image'];

                    return $item;
                });
            }
        } catch (\Exception $e) {
            return dataReturn(-5, formatErrMsg($e));
        }

        return dataReturn(0, 'success', $userBoxList);
    }

    /**
     * 仓库-商品列表
     * @param $param
     * @return array
     */
    public function getBagGoodsList($param)
    {
        if (!checkOpen()) {
            return dataReturn(404, '站点正在维护');
        }

        $userInfo = getUserInfo();
        $where[] = ["d.user_id", "=", $userInfo['id']];
        if(!empty($param['status'])){
            $where[] = ["o.status", "=", $param['status']];
        }
        $orderDetailModel = new OrderDetail();
        $bagGoodsList = $orderDetailModel->alias('d')
            ->field(['d.id','d.order_id','d.goods_name','d.goods_image','d.create_time','o.status', 'o.order_no', 'd.num'])
            ->leftJoin('order o','d.order_id = o.id')
            ->where($where)
            ->order('d.id desc')
            ->paginate($param['limit']);

        return dataReturn(0, 'success', $bagGoodsList);
    }

    /**
     * 仓库-单个盲盒详情
     * @param $param
     * @return array
     */
    public function getBagBoxDetail($param)
    {
        try {

            $userInfo = getUserInfo();

            $userBoxHotModel = new UserBoxHot();
            $userBoxHotList = $userBoxHotModel
                ->field('goods_id,tag_name,count(*) as `t_total`,goods_name,goods_image')
                ->where('blindbox_id', $param['blindbox_id'])->where('user_id', $userInfo['id'])
                ->group('goods_id')->select()->toArray();

            $goodIds = [];
            foreach ($userBoxHotList as $vo) {
                $goodIds[] = $vo['goods_id'];
            }

            $goodsList = (new Goods())->getAllList([
                ['id', 'in', $goodIds]
            ], 'id,recovery_price')['data'];
            $goods2Info = [];
            foreach ($goodsList as $vo) {
                $goods2Info[$vo['id']] = $vo;
            }

            foreach ($userBoxHotList as $key => $vo) {
                $goodsInfo = $goods2Info[$vo['goods_id']];
                $userBoxHotList[$key]['recovery_price'] = round($goodsInfo['recovery_price'] * $vo['t_total'], 2);
            }

        } catch (\Exception $e) {
            return dataReturn(-1, formatErrMsg($e));
        }

        return dataReturn(0, 'success', $userBoxHotList);
    }

    /**
     * 仓库-单个商品详情
     * @param $param
     * @return array
     */
    public function getBagGoodsDetail($param)
    {
        $orderDetailModel = new OrderDetail();
        $bagGoodsInfo = $orderDetailModel->findOne(
            ['id' => $param['orderdetail_id'],'user_id' => $param['user_info']['id']],
            ['id,goods_name,goods_image,create_time']);

        return dataReturn(0, 'success', $bagGoodsInfo);
    }

    /**
     * 盲盒奖品兑换
     * @param $param
     * @return array
     */
    public function bagBoxExchange($param)
    {
        $userInfo = getUserInfo();

        $luaScript = new LuaScript();
        $key = 'box_operate_' . $userInfo['id'];
        $luaScript->lock($key);

        Db::startTrans();
        try {

            $blindboxBagModel = new UserBoxHot(); // 盒子中
            $where[] = ['goods_id', 'in', $param['goods_id']];
            $where[] = ['user_id', '=', $userInfo['id']];
            $where[] = ['blindbox_id', '=', $param['blindbox_id']];

            $userBagList = $blindboxBagModel->with(['orderRecordDetail'])->where($where)->select()->toArray();
            if (empty($userBagList)) {
                Db::commit();
                return dataReturn(-1, '该奖品不存在或已被分解');
            }

            // 合成是的商品，回收价格特殊处理
            if ($param['blindbox_id'] == 0) {
                $goodsInfo = (new Goods())->field('id,recovery_price')->whereIn('id', $param['goods_id'])
                    ->select()->toArray();
                $id2Price = [];
                foreach ($goodsInfo as $vo) {
                    $id2Price[$vo['id']] = $vo['recovery_price'];
                }

                foreach ($userBagList as $key => $vo) {
                    $userBagList[$key]['recovery_price'] = $id2Price[$vo['goods_id']];
                }
            }

            $totalAmount = 0;
            $uuids = [];
            $recordDetailIds = [];
            foreach ($userBagList as $vo) {
                $totalAmount += $vo['recovery_price'];
                $uuids[] = $vo['uuid'];
                $recordDetailIds[] = $vo['record_detail_id'];
            }

            // 1、记录兑换记录表
            $exchangeData = [
                'exchange_no' => makeOrderNo('E'),
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'exchange_num' => count($userBagList),
                'total_amount' => $totalAmount,
                'status' => 2,
                'create_time' => date('Y-m-d H:i:s')
            ];
            $exchangeId = (new UserBoxExchange())->insertGetId($exchangeData);

            // 2、记录对象记录详情表
            $exchangeDetailData = [];
            foreach ($userBagList as $vo) {
                $exchangeDetailData[] = [
                    'exchange_id' => $exchangeId,
                    'user_id' => $userInfo['id'],
                    'user_box_uuid' => $vo['uuid'],
                    'record_detail_id' => $vo['record_detail_id'],
                    'goods_id' => $vo['goods_id'],
                    'num' => 1,
                    'amount' => $vo['recovery_price'],
                    'create_time' => date('Y-m-d H:i:s')
                ];
            }
            (new UserBoxExchangeDetail())->insertAll($exchangeDetailData);

            // 3、记录用户哈希币变动
            (new UserIntegralChangeLog())->insert([
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'integral' => $totalAmount,
                'type' => 1,
                'exchange_id' => $exchangeId,
                'create_time' => date('Y-m-d H:i:s')
            ]);

            // 4、删除用户盒子热点表
            (new UserBoxHot())->whereIn('uuid', $uuids)->delete();

            // 5、标记用户盒子记录表
            (new UserBoxLog())->whereIn('uuid', $uuids)->update([
                'status' => 2, // 已兑换
                'exchange_time' => date('Y-m-d H:i:s'),
                'update_time' => date('Y-m-d H:i:s')
            ]);

            // 6、增加用户哈希币
            $res = CapitalChange::integralInc([
                'amount' => $totalAmount,
                'user_id' => $userInfo['id']
            ]);
            if ($res['code'] != 0) {
                throw new Exception('系统异常');
            }

            // 7、标记中奖详情状态
            $orderRecordDetail = new OrderRecordDetail();
            $orderRecordDetail->whereIn('id', $recordDetailIds)->update([
                'status' => 2, // 已兑换
                'update_time' => now()
            ]);

            Db::commit();
            $luaScript->release($key);
            return dataReturn(0, '分解成功');
        } catch (\Exception $e) {
            Db::rollback();
            $luaScript->release($key);
            return dataReturn(-10, '分解异常', formatErrMsg($e));
        }
    }

    /**
     * 盲盒发货试算
     * @param $param
     * @param $type 1:api试算调用  2:内部试算调用
     * @return array
     */
    public function bagBoxTrail($param, $type = 1)
    {
        // TODO 后续运费模板 按模板
        if (empty($param['address_id'])) {
            return dataReturn(-1, '请选择收货地址');
        }

        // 校验goods的合法性
        $userInfo = getUserInfo();
        $goodsInfo = (new UserBoxHot())->getAllList([
            'blindbox_id' => $param['blindbox_id'],
            'user_id' => $userInfo['id']
        ], 'goods_id')['data'];

        $goodsIds = explode(',', $param['goods_id']);
        $boxGoodsIds = [];
        foreach ($goodsInfo as $vo) {
            $boxGoodsIds[] = $vo['goods_id'];
        }

        $diffArr = array_diff($goodsIds, $boxGoodsIds);
        if (!empty($diffArr)) {
            return dataReturn(-2, '要发货的商品有误');
        }

        $bagList = [];
        if ($type == 2) {
            $blindboxBagModel = new UserBoxHot();
            $where[] = ['blindbox_id', '=', $param['blindbox_id']];
            $where[] = ['user_id', '=', $userInfo['id']];
            $where[] = ['goods_id', 'in', $goodsIds];
            $userBagList = $blindboxBagModel->getAllList($where)['data']->toArray();
            if (empty($userBagList)) {
                return dataReturn(-3, '发货奖品不存在');
            }

            // 要发货的奖品
            foreach ($userBagList as $vo) {
                $bagList['id'][] = $vo['id'];
                $bagList['uuid'][] = $vo['uuid'];
            }
        }

        // 取所有商品中个邮费最大的那个当邮费
        $deliveryFee = (new Goods())->whereIn('id', $param['goods_id'])->max('delivery_fee');
        return dataReturn(0, $bagList, $deliveryFee);
    }

    /**
     * 盲盒发货邮费订单
     * @param $param
     * @return array
     */
    public function boxDeliver($param)
    {
        $userInfo = getUserInfo();
        $param['type'] = 2; // 未申请的

        $luaScript = new LuaScript();
        $key = 'box_operate_' . $userInfo['id'];
        $luaScript->lock($key);

        Db::startTrans();
        try {

            $trailData = $this->bagBoxTrail($param, 2);
            if ($trailData['code'] != 0) {
               throw new \Exception($trailData['msg']);
            }

            $deliveryFee = $trailData['data'];
            $orderNo = makeOrderNo('E');
            $payNo = makeOrderNo('E');

            // 写入邮费订单
            $orderInfo = [
                'order_no' => $orderNo,
                'pay_no' => $payNo,
                'amount' => $deliveryFee,
                'pay_way' => $param['pay_way'],
                'box_type' => $param['type'], // 盒子中
                'pay_status' => $deliveryFee > 0 ? 1 : 2,
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'address_id' => $param['address_id'],
                'express_content' => json_encode($trailData['msg']),
                'create_time' => now()
            ];

            if ($deliveryFee == 0) {
                $orderParam['pay_time'] = now();
            }

            $expressOrderInfo = (new OrderExpress())->insertOne($orderInfo);
            $orderInfo['express_order_id'] = $expressOrderInfo['data'];

            // 如果已支付，则直接完成
            if ($orderInfo['pay_status'] == 2) {
                $res = $this->completeExpressOrder($orderInfo, $param['type']);
                if ($res['code'] != 0) {
                    throw new \Exception($res['msg']);
                }

                $luaScript->release($key);
                Db::commit();
                return $res;
            }

            // 拼装参数执行支付
            if ($param['pay_way'] == 4) {
                $orderInfo['pay_way'] = 40;
            }

            $orderParam = [
                'id' => $expressOrderInfo['data'],
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'pay_price' => $orderInfo['amount'],
                'host' => getConfByType('api_url')['api_url'],
                'pay_order_no' => $orderInfo['pay_no'],
                'subject' => '邮费支付'
            ];

            $payProvider = (new PayProvider($orderInfo['pay_way']))->getStrategy();
            $res = Tools::payByPlatForm($payProvider, $param['platform'] , $orderParam);

            if ($orderInfo['pay_way'] == 40) {
                if ($res['code'] != 0) {
                    throw new \Exception($res['msg']);
                }

                $res = $this->completeExpressOrder($orderInfo, $param['type']);
                if ($res['code'] != 0) {
                    throw new \Exception($res['msg']);
                }

                $luaScript->release($key);
                Db::commit();
                return $res;
            }

            $luaScript->release($key);
            Db::commit();
            return dataReturn(201, $payNo, $res); // 需要支付
        } catch (\Exception $e) {
            $luaScript->release($key);
            Db::rollback();
            Log::error('发货系统异常:' . formatErrMsg($e));
            return dataReturn(-10, $e->getMessage());
        }
    }

    /**
     * 提货确认
     * @param $param
     * @return array
     */
    public function confirmBoxDeliver($param)
    {
        $userInfo = getUserInfo();

        $deliverModel = new UserBoxDeliver();
        $where[] = ['deliver_no', '=', $param['deliver_no']];
        $where[] = ['user_id', '=', $userInfo['id']];
        $deliverInfo = $deliverModel->findOne($where)['data'];
        if (empty($deliverInfo)) {
            return dataReturn(-2, '确认失败');
        }

        if ($deliverInfo['status'] != 2) {
            return dataReturn(-3, '暂不可以确认收货');
        }

        $deliverModel->updateById([
            'status' => 3,
            'update_time' => date('Y-m-d H:i:s')
        ], $deliverInfo['id']);

        return dataReturn(0, '操作成功');
    }

    /**
     * 确认收货
     * @param $param
     * @return array
     */
    public function bagGoodsConfirm($param)
    {
        $userInfo = getUserInfo();
        $where[] = ['id', '=', $param['order_id']];
        $where[] = ['user_id', '=', $userInfo['id']];

        $orderModel = new Order();
        $orderInfo = $orderModel->findOne($where)['data'];
        if (empty($orderInfo)) {
            return dataReturn(-1, '订单信息错误');
        }

        if ($orderInfo['status'] != 3) {
            return dataReturn(-2, '该订单不可以收货');
        }

        $orderModel->updateById([
            'status' => 5,
            'received_time' => date('Y-m-d H:i:s'),
            'update_time' => date('Y-m-d H:i:s')
        ], $param['order_id']);

        return dataReturn(0, '操作成功');
    }

    /**
     * 完成发货订单
     * @param $orderInfo
     * @param $type
     * @return array
     */
    public function completeExpressOrder($orderInfo, $type)
    {
        Db::startTrans();
        try {

            $boxInfo = json_decode($orderInfo['express_content'], true);
            $boxIds = $boxInfo['id'];

            // 盒子中的奖品数据
            if ($type == 1) {
                $blindboxBagModel = new UserBoxLog(); // 全部
            } else {
                $blindboxBagModel = new UserBoxHot(); // 盒子中
            }

            $where[] = ['id', 'in', $boxIds]; // box_id 形如 1,2,3
            $where[] = ['user_id', '=', $orderInfo['user_id']];
            if ($type == 1) {
                $where[] = ['status', '=', 1];
            }
            $userBagList = $blindboxBagModel->where($where)->select()->toArray();
            if (empty($userBagList)) {
                Db::commit();
                return dataReturn(-1, '该奖品不存在');
            }

            // 查询地址信息
            $addressInfo = (new UserAddress())->findOne([
                'id' => $orderInfo['address_id'],
                'user_id' => $orderInfo['user_id']
            ], 'receiver,phone,province_name,city_name,area_name,address')['data'];

            if (empty($addressInfo)) {
                return dataReturn(-12, '收货地址错误');
            }

            // 1、记录提货表
            $deliverId = (new UserBoxDeliver())->insertGetId([
                'deliver_no' => makeOrderNo('FH'),
                'express_order_id' => $orderInfo['express_order_id'],
                'address_id' => $orderInfo['address_id'],
                'address_info' => json_encode($addressInfo),
                'user_id' => $orderInfo['user_id'],
                'type' => 1,
                'status' => 1, // 待发货
                'postage_fee' => $orderInfo['amount'],
                'create_time' => now()
            ]);

            // 2、记录提货详情表
            $deliverDetail = [];
            $uuids = [];
            $recordDetailIds = [];
            foreach ($userBagList as $vo) {
                $uuids[] = $vo['uuid'];

                $deliverDetail[] = [
                    'deliver_id' => $deliverId,
                    'user_id' => $orderInfo['user_id'],
                    'user_box_uuid' => $vo['uuid'],
                    'record_detail_id' => $vo['record_detail_id'],
                    'create_time' => now()
                ];

                $recordDetailIds[] = $vo['record_detail_id'];
            }
            (new UserBoxDeliverDetail())->insertAll($deliverDetail);

            // 3、删除用户盒子热点表
            (new UserBoxHot())->whereIn('uuid', $uuids)->delete();

            // 4、标记用户盒子记录表
            (new UserBoxLog())->whereIn('uuid', $uuids)->update([
                'status' => 3, // 已提货
                'exchange_time' => now(),
                'update_time' => now()
            ]);

            // 5、标记中奖详情状态
            $orderRecordDetail = new OrderRecordDetail();
            $orderRecordDetail->whereIn('id', $recordDetailIds)->update([
                'status' => 3, // 已提货
                'update_time' => now()
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('发货邮费支付异常: ' . formatErrMsg($e));
            return dataReturn(-1, '支付异常');
        }

        return dataReturn(0, '支付成功');
    }
}
