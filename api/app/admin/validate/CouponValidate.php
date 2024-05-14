<?php
namespace app\admin\validate;

use think\Validate;

class CouponValidate extends Validate
{
    protected $rule = [
        'name|优惠券名' => 'require',
        'type|优惠券类型' => 'require|number',
        'is_limit_num|发放量限制' => 'require|number',
        'max_receive_num|最多领取数量' => 'require|number',
        'validity_type|有效期类型' => 'require|number',
        'join_blindbox|参与方式' => 'require|number'
    ];
}