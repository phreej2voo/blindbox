<?php

namespace app\api\service\marketing;

use app\model\marketing\Coupon;
use app\model\marketing\CouponBlindbox;
use app\model\marketing\UserCoupon;

class CouponService
{
    /**
     * 获取我的优惠券列表
     * @param $param
     * @return array
     */
    public function getMyCouponList($param)
    {
        $userCouponModel = new UserCoupon();
        // 一键重置过期
        $userCouponModel->updateByWhere([
            'status' => 3,
            'update_time' => now()
        ], [['end_time', '<', now()]]);

        if ($param['type'] == 1) {
            $status = [1];
        } else {
            $status = [2, 3];
        }

        $where[] = ['user_id', '=', $param['user_id']];
        $where[] = ['status', 'in', $status];
        $list = $userCouponModel->field('id,coupon_id,start_time,end_time,status')->with(['couponInfo'])->where($where)->paginate($param['limit']);

        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取可用的优惠券
     * @param $param
     * @return array
     */
    public function getMyValidCoupon($param)
    {
        $userCouponModel = new UserCoupon();
        // 一键重置过期
        $userCouponModel->updateByWhere([
            'status' => 3,
            'update_time' => now()
        ], [['end_time', '<', now()]]);

        $couponList = $userCouponModel->getAllList([
            'user_id' => $param['user_id'],
            'status' => 1
        ])['data']->toArray();

        $couponIds = [];
        foreach ($couponList as $vo) {
            $couponIds[] = $vo['coupon_id'];
        }

        $couponInfo = (new Coupon())->getAllList([
            ['id', 'in', array_unique($couponIds)]
        ])['data'];

        $couponId2Info = [];
        $couponBlindboxModel = new CouponBlindbox();
        foreach ($couponInfo as $vo) {
            // 指定盲盒
            if ($vo['join_blindbox'] == 2) {
                $has = $couponBlindboxModel->findOne([
                    'coupon_id' => $vo['id'],
                    'blindbox_id' => $param['blindbox_id']
                ], 'id')['data'];

                if (!empty($has)) {
                    $couponId2Info[$vo['id']] = $vo;
                }

                continue;
            }

            // 有门槛
            if ($vo['is_threshold'] == 1) {
                if ($vo['threshold_amount'] <= $param['amount']) {
                    $couponId2Info[$vo['id']] = $vo;
                }

                continue;
            }

            $couponId2Info[$vo['id']] = $vo;
        }

        foreach ($couponList as $key => $vo) {
            if (!isset($couponId2Info[$vo['coupon_id']])) {
                unset($couponList[$key]);
            } else {
                $couponList[$key]['coupon_info'] = [
                    'type' => $couponId2Info[$vo['coupon_id']]['type'],
                    'discount' => $couponId2Info[$vo['coupon_id']]['discount'],
                    'amount' => $couponId2Info[$vo['coupon_id']]['amount'],
                ];
            }
        }

        return dataReturn(0, 'success', array_values($couponList));
    }
}