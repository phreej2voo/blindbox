<?php

namespace app\admin\validate;

use think\Validate;

class HomeADValidate extends Validate
{
    protected $rule = [
        'type|类型' => 'require',
        'pic|图片地址' => 'require',
        'status|状态' => 'require'
    ];
}