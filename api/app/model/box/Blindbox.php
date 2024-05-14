<?php

namespace app\model\box;

use app\model\BaseModel;

class Blindbox extends BaseModel
{
    const INDEX_TYPE_ARR = [1=>'新人专享',2=>'今日推荐',3=>'首页显示'];
    public function detail()
    {
        return $this->hasMany(BlindboxGoods::class, 'blindbox_id', 'id')->visible(['goods_image'])->limit(4);
    }

    public function orderDetail()
    {
        return $this->hasMany(BlindboxGoods::class, 'blindbox_id', 'id')->visible(['goods_name', 'goods_image', 'price', 'recovery_price']);
    }
}
