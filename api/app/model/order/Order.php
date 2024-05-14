<?php

namespace app\model\order;

use app\model\BaseModel;
use app\model\box\Blindbox;
use app\model\user\User;

class Order extends BaseModel
{
    public function blindbox()
    {
        return $this->hasOne(Blindbox::class, 'id', 'blindbox_id')->visible(['name', 'pic']);
    }

    public function deliverDetail()
    {
        return $this->hasOne(OrderDeliverDetail::class, 'order_id', 'id');
    }

    public function avatar()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->visible(['avatar']);
    }
}