<?php
namespace app\admin\validate;

use think\Validate;

class SendCouponValidate extends Validate
{
    protected $rule = [
        'name|优惠券名' => 'require',
        'num|发放数量' => 'require|number',
        'userList|发放用户' => 'require'
    ];
}