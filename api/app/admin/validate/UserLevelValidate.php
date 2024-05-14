<?php

namespace app\admin\validate;

use think\Validate;

class UserLevelValidate extends Validate
{
    protected $rule = [
        'title|等级名称' => 'require|max:155',
        'level|等级值' => 'require|number',
        'experience|经验值' => 'require|number'
    ];
}