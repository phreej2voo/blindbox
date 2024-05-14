<?php
namespace app\model\marketing;

use app\model\BaseModel;

class ConflateProgress extends BaseModel
{
    public function conflate()
    {
        return $this->hasOne(Conflate::class, 'id', 'conflate_id')->visible(['goods_name', 'image', 'stock']);
    }

    public function detail()
    {
        return $this->hasMany(ConflateDetail::class, 'progress_id', 'id')->visible(['goods_name', 'goods_image']);
    }
}