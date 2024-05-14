<?php

namespace app\model\marketing;

use app\model\BaseModel;

class RollUser extends BaseModel
{
    public function roll()
    {
        return $this->hasOne(Roll::class, 'id', 'roll_id')->visible(['title', 'status', 'avatar']);
    }
}
