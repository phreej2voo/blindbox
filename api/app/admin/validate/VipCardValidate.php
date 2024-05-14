<?php

namespace app\admin\validate;

use think\Validate;

class VipCardValidate extends Validate
{
    protected $rule = [
        'type|会员卡类型' => 'require|number',
        'title|会员卡名' => 'require|max:155',
        'price|价格' => 'require|float',
        'stock|库存' => 'require',
        'one_limit|每人限制' => 'require',
        'status|状态' => 'require',
        'equity|权益' => 'require'
    ];
}