<?php

namespace app\admin\service\user;

use app\admin\validate\UserBoxDeliverValidate;
use app\model\order\OrderExpress;
use app\model\user\User;
use app\model\user\UserBoxDeliver;
use app\model\user\UserBoxDeliverDetail;
use think\exception\ValidateException;
use think\facade\Db;

class UserBoxDeliverService
{
    /**
     * 获取奖品发货列表
     * @param $param
     * @return array
     */
    public function getList($param): array
    {
        $where[] = ['pid', '<>', -1];
        if (!empty($param['deliver_no'])) {
            $where[] = ['deliver_no', '=', $param['deliver_no']];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        if (!empty($param['user_id'])) {
            $where[] = ['user_id', '=', $param['user_id']];
        }

        try {

            $userBoxDeliverModel = new UserBoxDeliver();
            $list = $userBoxDeliverModel->where($where)->order('id desc')->paginate($param['limit']);
            // 待发货订单
            $num = $userBoxDeliverModel->where('status', 1)->count('id');

            if (!empty($list)) {
                $userIds = [];
                foreach ($list as &$item) {
                    $userIds[] = $item['user_id'];
                    $item['express'] = json_decode($item['express'], true);
                }
                if (!empty($userIds)) {
                    $userModel = new User();
                    $userWhere[] = ['id', 'in', $userIds];
                    $res = $userModel->where($userWhere)->field(['id', 'nickname'])->select()->toArray();
                    if (!empty($res)) {
                        $userNames = [];
                        foreach ($res as $re) {
                            $userNames[$re['id']] = $re['nickname'];
                        }
                        foreach ($list as &$item) {
                            $item['user_name'] = $userNames[$item['user_id']] ?? '';
                        }
                    }
                }
            }

            $return = pageReturn(dataReturn(0, '', $list));
            $return['data']['not_express'] = $num;
            return $return;
        } catch (\Exception $e) {
            return dataReturn(-1, $e->getMessage());
        }
    }

    /**
     * Notes: 发货
     * Author: LJS
     * @param $param
     * @return array
     */
    public function deliver($param): array
    {
        try {
            validate(UserBoxDeliverValidate::class)->scene('deliver')->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        // 处理分单
        if (!empty($param['splitOrder'])) {
            return $this->splitSendOrder($param);
        }

        unset($param['splitOrder'], $param['detailIds']);
        $param['update_time'] = date('Y-m-d H:i:s');
        $param['status'] = 2; // 已发货

        $userBoxDeliverModel = new UserBoxDeliver();
        $res = $userBoxDeliverModel->updateById($param, $param['id']);
        if ($res['code'] != 0) {
            return $res;
        }

        return dataReturn(0, '发货成功');
    }

    /**
     * 获取订单信息详情
     * @param $id
     * @return array
     */
    public function getOrderInfo($id)
    {
        $orderExpressModel = new OrderExpress();
        $info = $orderExpressModel->findOne(['id' => $id])['data'];
        if (empty($info)) {
            return dataReturn(0);
        }

        $payStatus = config('pay.pay_status');
        $info['status_txt'] = $payStatus[$info['pay_status']];

        $payWay = config('pay.pay_way');
        $info['pay_way_txt'] = $payWay[$info['pay_way']];

        return dataReturn(0, 'success', $info);
    }

    /**
     * 拆单发货
     * @param $param
     * @return array
     */
    protected function splitSendOrder($param)
    {
        Db::startTrans();
        try {

            $orderDeliverDetailModel = new UserBoxDeliverDetail();
            $orderDeliverModel = new UserBoxDeliver();

            $totalGoods = $orderDeliverDetailModel->where('deliver_id', $param['id'])->count('id');
            // 选取全部的订单，则不拆单
            if (count($param['detailIds']) == $totalGoods) {
                $param['update_time'] = date('Y-m-d H:i:s');
                $param['status'] = 2; // 已发货

                unset($param['splitOrder'], $param['detailIds']);
                $userBoxDeliverModel = new UserBoxDeliver();
                $res = $userBoxDeliverModel->updateById($param, $param['id']);
                if ($res['code'] != 0) {
                    return $res;
                }
            } else {

                // 开始拆单
                $orderDeliverInfo = $orderDeliverModel->findOne(['id' => $param['id']])['data'];
                $detailList = $orderDeliverDetailModel->getAllList(['deliver_id' => $param['id']])['data'];

                // 写已发货单
                $deliverId = $orderDeliverModel->insertGetId([
                    'pid' => $orderDeliverInfo['id'],
                    'deliver_no' => makeOrderNo('FH'),
                    'express_order_id' => $orderDeliverInfo['express_order_id'],
                    'address_id' => $orderDeliverInfo['address_id'],
                    'address_info' => $orderDeliverInfo['address_info'],
                    "express_name" => $param['express_name'],
                    "express_code" => $param['express_code'],
                    "express_no" => $param['express_no'],
                    'user_id' => $orderDeliverInfo['user_id'],
                    'type' => $orderDeliverInfo['type'],
                    'status' => 2, // 已发货
                    'postage_fee' => $orderDeliverInfo['postage_fee'],
                    'create_time' => now()
                ]);

                $alreadySend = [];
                $notSend = [];
                foreach ($detailList as $vo) {
                    if (in_array($vo['id'], $param['detailIds'])) {
                        $alreadySend[] = $vo;
                    } else {
                        $notSend[] = $vo;
                    }
                }

                $deliverDetail = [];
                foreach ($alreadySend as $vo) {
                    $deliverDetail[] = [
                        'deliver_id' => $deliverId,
                        'user_id' => $vo['user_id'],
                        'user_box_uuid' => $vo['user_box_uuid'],
                        'record_detail_id' => $vo['record_detail_id'],
                        'create_time' => now()
                    ];
                }
                $orderDeliverDetailModel->insertAll($deliverDetail);

                // 写剩下的未发货单
                $deliverId = $orderDeliverModel->insertGetId([
                    'pid' => $orderDeliverInfo['id'],
                    'deliver_no' => makeOrderNo('FH'),
                    'express_order_id' => $orderDeliverInfo['express_order_id'],
                    'address_id' => $orderDeliverInfo['address_id'],
                    'address_info' => $orderDeliverInfo['address_info'],
                    'user_id' => $orderDeliverInfo['user_id'],
                    'type' => $orderDeliverInfo['type'],
                    'status' => 1, // 已发货
                    'postage_fee' => $orderDeliverInfo['postage_fee'],
                    'create_time' => now()
                ]);

                $deliverDetail = [];
                foreach ($notSend as $vo) {
                    $deliverDetail[] = [
                        'deliver_id' => $deliverId,
                        'user_id' => $vo['user_id'],
                        'user_box_uuid' => $vo['user_box_uuid'],
                        'record_detail_id' => $vo['record_detail_id'],
                        'create_time' => now()
                    ];
                }
                $orderDeliverDetailModel->insertAll($deliverDetail);

                // 将旧订单过期
                $orderDeliverModel->where('id', $param['id'])->update([
                    'pid' => -1,
                    'update_time' => now()
                ]);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, '发货失败');
        }

        return dataReturn(0, '发货成功');
    }
}