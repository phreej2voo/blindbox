<?php

namespace app\model\marketing;

use app\model\BaseModel;

class Roll extends BaseModel
{
    public function user()
    {
        return $this->hasMany(RollUser::class, 'roll_id', 'id')->visible(['username', 'avatar', 'hot'])->limit(7);
    }

    public function goods()
    {
        return $this->hasMany(RollDetail::class, 'roll_id', 'id')->visible(['goods_name', 'image', 'recovery_price']);
    }
}
