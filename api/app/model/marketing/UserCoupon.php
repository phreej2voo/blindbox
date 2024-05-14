<?php

namespace app\model\marketing;

use app\model\BaseModel;

class UserCoupon extends BaseModel
{
    public function coupon()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id');
    }

    public function couponInfo()
    {
        return $this->hasOne(Coupon::class, 'id', 'coupon_id')->visible(['id', 'name', 'type', 'discount', 'amount']);
    }
}