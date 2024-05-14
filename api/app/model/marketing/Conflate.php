<?php
namespace app\model\marketing;

use app\model\BaseModel;

class Conflate extends BaseModel
{
    public function goods()
    {
        return $this->hasMany(ConflateGoods::class, 'conflate_id', 'id');
    }
}