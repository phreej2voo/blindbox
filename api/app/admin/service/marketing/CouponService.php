<?php

namespace app\admin\service\marketing;

use app\admin\validate\CouponValidate;
use app\model\goods\Goods;
use app\model\marketing\Coupon;
use app\model\marketing\CouponBlindbox;
use think\exception\ValidateException;
use think\facade\Db;

class CouponService
{
    public function checkParam($id)
    {
        $goods = new Goods();
        if ($goods->where('coupon_id',$id)->where('delete_flag',1)->where('status',1)->find()) {
            return dataReturn(-1, '请先下架商品再关闭优惠券');
        }
        return dataReturn(0);
        // 如果在商城中出现优惠券，必须先下掉商品才能关闭优惠券

    }
    /**
     * 获取优惠券列表
     * @param $param
     * @return array
     */
    public function getCouponList($param)
    {
        $where = [];
        if (!empty($param['name'])) {
            $where[] = ['name', 'like', '%' . $param['name'] . '%'];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        $couponModel = new Coupon();
        $couponStatus = config('system.coupon_status');
        $list = $couponModel->where($where)->with('blindbox')->paginate($param['limit'])
            ->each(function ($item) use ($couponStatus) {
            $item->status_txt = $couponStatus[$item->status];
        });

        return pageReturn(dataReturn(0, 'success', $list));
    }

    /**
     * 获取盲优惠券下拉列表
     * @return array
     */
    public function selectCouponList()
    {
        /** @var  $where */
        $where = [];
        $where[] = ['status', '=', 1];

        return (new Coupon())->getAllList($where, '*', 'id asc');
    }

    /**
     * 添加优惠券
     * @param $param
     * @return array
     */
    public function addCoupon($param)
    {
        try {
            validate(CouponValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        if ($param['type'] == 1) {
            if (empty($param['amount'])) {
                return dataReturn(-2, '优惠券面额需要大于0');
            }
        } else {
            if (empty($param['discount'])) {
                return dataReturn(-3, '优惠折扣需要大于0');
            }
        }

        if ($param['is_limit_num'] == 1 && empty($param['total_num'])) {
            return dataReturn(-4, '发放数量要大于等于0');
        }

        if ($param['is_threshold'] == 1 && (!is_numeric($param['threshold_amount']) || $param['threshold_amount'] < 0)) {
            return dataReturn(-5, '门槛金额要大于等于0');
        }

        if ($param['validity_type'] == 1) {
            if (empty($param['datetime_range'])) {
                return dataReturn(-6, '有效期不能为空');
            } else {
                $param['start_time'] = $param['datetime_range'][0];
                $param['end_time'] = $param['datetime_range'][1];
            }
        } else {
            if (empty($param['receive_useful_day'])) {
                return dataReturn(-7, '领取有效期不能为空');
            }
        }
        unset($param['datetime_range']);

        if ($param['join_blindbox'] == 2 && empty($param['selectedGoods'])) {
            return dataReturn(-8, '活动商品不能为空');
        }

        Db::startTrans();
        try {

            $joinGoods = $param['selectedGoods'] ?? [];
            unset($param['selectedGoods']);
            $param['create_time'] = now();

            $couponModel = new Coupon();
            $couponId = $couponModel->insertGetId($param);

            if (!empty($joinGoods)) {
                $blindboxBatch = [];
                foreach ($joinGoods as $vo) {
                    $blindboxBatch[] = [
                        'coupon_id' => $couponId,
                        'blindbox_id' => $vo['id'],
                        'blindbox_info' => json_encode($vo)
                    ];
                }

                $couponBlindboxModel = new CouponBlindbox();
                $couponBlindboxModel->insertAll($blindboxBatch);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-9, $e->getMessage());
        }

        return dataReturn(0, '新增成功');
    }

    /**
     * 开启关闭优惠券
     * @param $param
     * @return array
     */
    public function openCoupon($param)
    {
        $data = $this->checkParam($param['id']);
        if ($data['code'] != 0) {
            return $data;
        }

        $couponModel = new Coupon();
        $couponModel->updateById([
            'open_flag' => $param['open_flag'],
            'update_time' => now()
        ], $param['id']);

        return dataReturn(0, '操作成功');
    }

    /**
     * 作废优惠券
     * @param $id
     * @return array
     */
    public function delCoupon($id)
    {
        $data = $this->checkParam($id);
        if ($data['code'] != 0) {
            return $data;
        }
        $couponModel = new Coupon();
        $couponModel->updateById([
            'status' => 2, // 作废
            'update_time' => now()
        ], $id);

        return dataReturn(0, '操作成功');
    }

    /**
     * 获取全部的优惠券
     * @return array
     */
    public function getAllCoupon()
    {
        $couponModel = new Coupon();
        return $couponModel->getAllList(['status' => 1], 'id,name');
    }
}
