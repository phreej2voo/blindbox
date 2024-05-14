<?php

namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\CouponService;
use app\admin\service\marketing\UserCouponService;

class Coupon extends Base
{
    /**
     * 优惠券列表
     */
    public function index()
    {
        $couponService = new CouponService();
        return json($couponService->getCouponList(input('param.')));
    }

    /**
     * 添加优惠券
     */
    public function add()
    {
        $couponService = new CouponService();
        return json($couponService->addCoupon(input('post.')));
    }

    /**
     * 获取盲优惠券下拉列表
     * @return \think\response\Json
     */
    public function list()
    {
        return json((new CouponService())->selectCouponList());
    }

    /**
     * 作废优惠券
     */
    public function del()
    {
        $couponService = new CouponService();
        return json($couponService->delCoupon(input('param.id')));
    }

    /**
     * 改变状态
     */
    public function open()
    {
        $couponService = new CouponService();
        return json($couponService->openCoupon(input('param.')));
    }

    /**
     * 单个优惠券领取记录
     */
    public function log()
    {
        $couponService = new UserCouponService();
        return json($couponService->getCouponReceiveLog(input('param.')));
    }

    /**
     * 优惠券领取记录
     */
    public function receive()
    {
        $couponService = new UserCouponService();
        return json($couponService->getReceiveLog(input('param.')));
    }

    /**
     * 获取全部的优惠券
     */
    public function getAllCoupon()
    {
        $couponService = new CouponService();
        return json($couponService->getAllCoupon());
    }

    /**
     * 发放优惠券
     */
    public function send()
    {
        $couponService = new UserCouponService();
        return json($couponService->sendCoupon(input('post.')));
    }
}