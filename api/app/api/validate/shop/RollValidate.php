<?php

namespace app\api\validate\shop;

use think\Validate;

class RollValidate extends Validate
{
    protected $rule = [
        'title|房间名' => 'require|max:155',
        'desc|描述' => 'require|max:155',
        'reward_time|开奖时间' => 'require',
        'password|密码口令' => 'require|max:15',
        'award|奖品' => 'require'
    ];
}