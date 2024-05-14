<?php

namespace app\model\user;

use app\model\BaseModel;
use app\model\box\Blindbox;
use app\model\box\BlindboxGoods;
use app\model\box\BlindboxTag;
use app\model\goods\Goods;
use app\model\order\OrderRecordDetail;

class UserBoxHot extends BaseModel
{
    public function orderRecordDetail()
    {
        return $this->hasOne(OrderRecordDetail::class, 'id', 'record_detail_id')->bind([
            'recovery_price' => 'recovery_price'
        ]);
    }

    public function blindbox()
    {
        return $this->hasOne(Blindbox::class, 'id', 'blindbox_id')->bind(['name', 'pic']);
    }

    public function conflate()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id')->bind(['conflate_val']);
    }
}