<?php

namespace strategy\play\impl;

use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use strategy\play\PlayInterface;

/**
 * 哈希赏
 */
class ForFan implements PlayInterface
{
    public function stock(&$awardPool, &$nowStock, $awardGoodsId)
    {
        return true;
    }

    public function updateData($param)
    {
        $data = $param['param'];
        // 维护盲盒总销量
        $blindboxModel = new Blindbox();
        $blindboxModel->where('id', $data['blindbox_id'])->inc('sales', $data['total_num'])->update();

        // 维护盲盒信息
        $blindboxBoxesModel = new BlindboxBoxes();
        $boxesInfo = $blindboxBoxesModel->where('blindbox_id', $data['blindbox_id'])->where('box_no', $data['box_id'])->find();
        $blindboxBoxesModel->where('id', $boxesInfo['id'])->update([
            'sales' => $boxesInfo['sales'] + $data['total_num'],
            'stock' => $boxesInfo['stock'] - $data['total_num'],
            'status' => (($boxesInfo['stock'] - $data['total_num']) <= 0) ? 2 : 1,
            'update_time' => now()
        ]);
    }

    public function award($param)
    {
        return true;
    }
}
