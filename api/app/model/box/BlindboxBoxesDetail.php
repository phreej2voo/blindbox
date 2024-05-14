<?php

namespace app\model\box;

use app\model\BaseModel;

class BlindboxBoxesDetail extends BaseModel
{
    public function goods()
    {
        return $this->hasOne(BlindboxGoods::class, 'goods_id', 'blindbox_goods_id')
            ->bind(['goods_id', 'goods_name', 'goods_image', 'price', 'recovery_price']);
    }

    public function goodsDetail()
    {
        return $this->hasOne(BlindboxGoods::class, 'goods_id', 'blindbox_goods_id')
            ->bind(['goods_id', 'goods_name', 'goods_image', 'price', 'probability']);
    }

    public function tag()
    {
        return $this->hasOne(BlindboxTag::class, 'id', 'tag_id')->bind(['tag_name' => 'name']);
    }
}