<?php

namespace app\model\marketing;

use app\model\BaseModel;

class VipCardUser extends BaseModel
{
    public function card()
    {
        return $this->hasOne(VipCard::class, 'id', 'card_id')->bind(['equity', 'type']);
    }
}