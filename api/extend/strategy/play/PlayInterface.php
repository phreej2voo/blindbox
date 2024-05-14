<?php

namespace strategy\play;

interface PlayInterface
{
    /**
     * 处理库存问题
     * @param $awardPool
     * @param $nowStock
     * @param $awardGoodsId
     * @return mixed
     */
    public function stock(&$awardPool, &$nowStock, $awardGoodsId);

    /**
     * 更新数据
     * @param $param
     * @return mixed
     */
    public function updateData($param);

    /**
     * 处理额外的奖励
     * @param $param
     * @return mixed
     */
    public function award($param);
}