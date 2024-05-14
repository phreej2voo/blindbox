<?php

namespace app\admin\validate;

use think\Validate;

class RollValidate extends Validate
{
    protected $rule = [
        'title|房间名' => 'require|max:155',
        'type|类型' => 'require',
        'desc|描述' => 'require|max:155',
        'reward_time|开奖时间' => 'require'
    ];
}