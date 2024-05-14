<?php

namespace strategy\reward\impl;

use app\model\marketing\Coupon;
use app\model\marketing\UserCoupon;
use strategy\reward\RewardInterface;
use think\facade\Db;

class CouponImpl implements RewardInterface
{
    public function getStatus($code, $userId, $status = 2)
    {
        $userCouponModel = new UserCoupon();
        $couponInfo = $userCouponModel->findOne([
            'source' => $status,
            'code' => $code,
            'user_id' => $userId
        ], 'status')['data'];

        return empty($couponInfo) ? 2 : (($couponInfo['status'] == 1) ? 3 : (($couponInfo['status'] == 2) ? 4 : 5));
    }

    public function receive($reward, $param, $source = 2)
    {
        Db::startTrans();
        try {

            $code = makeTaskCode($param['groupId'], $param['rewardId']);
            $userCouponModel = new UserCoupon();
            $hasReceived = $userCouponModel->where('code', $code)->where('user_id', $param['user_id'])->find();
            if (!empty($hasReceived)) {
                throw new \Exception('您已领取改奖励，请勿重复领取');
            }

            // 优惠券信息
            $couponInfo = (new Coupon())->where('id', $reward['resourceId'])->find();
            // 固定日期
            if ($couponInfo['validity_type'] == 1) {
                $startTime = $couponInfo['start_time'];
                $endTime = $couponInfo['end_time'];
            } else { // 领取之后
                $startTime = now();
                $endTime = date('Y-m-d H:i:s', strtotime('+' . $couponInfo['receive_useful_day'] . 'days'));
            }

            $coupon = [];
            for ($i = 0; $i < $reward['num']; $i++) {
                $coupon[] = [
                    'source' => $source,
                    'code' => $code,
                    'id_code' => uniqid(),
                    'coupon_id' => $reward['resourceId'],
                    'coupon_name' => $reward['name'],
                    'order_id' => 0,
                    'user_id' => $param['user_id'],
                    'username' => $param['username'],
                    'status' => 1,
                    'start_time' => $startTime,
                    'end_time' => $endTime,
                    'create_time' => now()
                ];
            }

            $userCouponModel->insertAll($coupon);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, $e->getMessage() ?? '领取失败');
        }

        return dataReturn(0, '领取成功');
    }

    public function getDetail($id)
    {
        return (new Coupon())->findOne(['id' => $id], 'name,type,amount,discount');
    }
}