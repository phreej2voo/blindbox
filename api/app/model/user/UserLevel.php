<?php

namespace app\model\user;

use app\model\BaseModel;

class UserLevel extends BaseModel
{
    protected function present()
    {
        return $this->hasMany(UserLevelPresent::class, 'level_id', 'id');
    }
}