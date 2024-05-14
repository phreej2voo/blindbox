<?php

namespace app\api\controller\v1\marketing;

use app\api\controller\Base;
use app\api\service\marketing\CouponService;

class Coupon extends Base
{
    /**
     * 我的优惠券
     */
    public function getMyCoupon(CouponService $couponService)
    {
        $param = input('param.');
        $param['user_id'] = getUserInfo()['id'];

        return json($couponService->getMyCouponList($param));
    }

    /**
     * 获取可用的优惠券
     */
    public function getValidCoupon(CouponService $couponService)
    {
        $param = input('param.');
        $param['user_id'] = getUserInfo()['id'];

        return json($couponService->getMyValidCoupon($param));
    }
}