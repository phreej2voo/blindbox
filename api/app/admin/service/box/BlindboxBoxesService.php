<?php

namespace app\admin\service\box;

use app\model\box\BlindboxBoxes;

class BlindboxBoxesService
{
    /**
     * 盒子列表
     * @param $param
     * @return array
     */
    public function getBoxesList($param)
    {
        $where[] = ['blindbox_id', '=', $param['blindbox_id']];
        $blindboxBoxes = new BlindboxBoxes();

        return $blindboxBoxes->getPageList($param['limit'], $where);
    }
}