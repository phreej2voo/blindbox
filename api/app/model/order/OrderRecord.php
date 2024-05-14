<?php

namespace app\model\order;

use app\model\BaseModel;
use app\model\box\Blindbox;

class OrderRecord extends BaseModel
{
    public function detail()
    {
        return $this->hasMany(OrderRecordDetail::class, 'order_record_id', 'id')->visible(['goods_id', 'goods_image', 'goods_name', 'goods_price','source', 'tag_id', 'user_id']);
    }

    public function box()
    {
        return $this->hasOne(Blindbox::class, 'id', 'blindbox_id')->visible(['name', 'price']);
    }
}
