<?php

namespace app\admin\service\marketing;

use app\admin\validate\SendCouponValidate;
use app\model\marketing\Coupon;
use app\model\marketing\UserCoupon;
use app\model\user\User;
use think\exception\ValidateException;
use think\facade\Db;

class UserCouponService
{
    /**
     * 获取单个优惠券领取记录
     * @param $param
     * @return array
     */
    public function getCouponReceiveLog($param)
    {
        $where[] = ['coupon_id', '=', $param['coupon_id']];
        if (!empty($param['user_id'])) {
            $where[] = ['user_id', '=', $param['user_id']];
        }

        $couponModel = new UserCoupon();
        $list = $couponModel->getPageList($param['limit'], $where);

        return pageReturn($list);
    }

    /**
     * 获取优惠券领取记录
     * @param $param
     * @return array
     */
    public function getReceiveLog($param)
    {
        $limit = $param['limit'];
        $status = $param['status'];
        $userName = $param['user_name'];

        $where = [];
        if (!empty($status)) {
            $where[] = ['status', '=', $status];
        }

        if (!empty($userName)) {
            $where[] = ['username', 'like', '%' . $userName . '%'];
        }

        $receiveCouponStatus = config('system.receive_coupon_status');
        $couponModel = new UserCoupon();
        $list = $couponModel->with('coupon')->where($where)->order('id desc')->paginate($limit)
            ->each(function ($item) use($receiveCouponStatus) {
            $item->status_txt = $receiveCouponStatus[$item->status];
        });

        return pageReturn(dataReturn(0, 'success', $list));
    }

    /**
     * 赠送优惠券
     * @param $param
     * @return array
     */
    public function sendCoupon($param)
    {
        try {
            validate(SendCouponValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        $userList = explode(',', $param['userList']);
        if (!is_array($userList)) {
            return dataReturn(-2, '发放用户格式有误');
        }

        Db::startTrans();
        try {

            $code = uniqid();
            // 优惠券信息
            $couponInfo = (new Coupon())->where('id', $param['id'])->find();
            // 固定日期
            if ($couponInfo['validity_type'] == 1) {
                $startTime = $couponInfo['start_time'];
                $endTime = $couponInfo['end_time'];
            } else { // 领取之后
                $startTime = now();
                $endTime = date('Y-m-d H:i:s', strtotime('+' . $couponInfo['receive_useful_day'] . 'days'));
            }

            // 获取发放用户人名
            $userInfoList = (new User())->field('id,nickname')->whereIn('id', $userList)->select()->toArray();
            $userMap = [];
            foreach ($userInfoList as $vo) {
                $userMap[$vo['id']] = $vo['nickname'];
            }

            $coupon = [];
            for ($i = 0; $i < $param['num']; $i++) {
                foreach ($userList as $vo) {
                    $coupon[] = [
                        'source' => 3, // 后台赠送
                        'code' => $code,
                        'id_code' => uniqid(),
                        'coupon_id' => $param['id'],
                        'coupon_name' => $couponInfo['name'],
                        'order_id' => 0,
                        'user_id' => $vo,
                        'username' => $userMap[$vo],
                        'status' => 1,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'create_time' => now()
                    ];
                }
            }

            (new UserCoupon())->insertAll($coupon);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, $e->getMessage() ?? '领取失败');
        }

        return dataReturn(0, '发放成功');
    }
}