<?php

namespace strategy\play\impl;

use app\model\box\BlindboxBoxes;
use strategy\play\PlayInterface;

/**
 * 哈希赏
 */
class RandFan implements PlayInterface
{
    public function stock(&$awardPool, &$nowStock, $awardGoodsId)
    {
        return true;
    }

    public function updateData($param)
    {
        $data = $param['param'];
        $totalCostPrice = $param['totalCostPrice'];
        $totalAmount = $param['totalAmount'];
        $profitLossAmount = round($param['totalCostPrice'] / $param['ratio'] - $param['totalAmount'], 2);

        // 维护盲盒信息
        $blindboxBoxesModel = new BlindboxBoxes();
        $boxesInfo = $blindboxBoxesModel->where('blindbox_id', $data['blindbox_id'])->where('box_no', $data['box_id'])->find();
        $blindboxBoxesModel->where('id', $boxesInfo['id'])->update([
            'sales_cost_price' => round($boxesInfo['sales_cost_price'] + $totalCostPrice, 2),
            'sales_amount' => round($boxesInfo['sales_amount'] + $totalAmount, 2),
            'profit_loss_amount' => $boxesInfo['profit_loss_amount'] + $profitLossAmount,
            'sales' => $boxesInfo['sales'] + $data['total_num'],
            'update_time' => now()
        ]);
    }

    public function award($param)
    {
        return true;
    }
}
