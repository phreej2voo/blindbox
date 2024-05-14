<?php

namespace app\model\marketing;

use app\model\BaseModel;

class Coupon extends BaseModel
{
    public function blindbox()
    {
        return $this->hasMany(CouponBlindbox::class, 'coupon_id', 'id');
    }
}