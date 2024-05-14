<?php

namespace app\admin\controller\box;

use app\admin\controller\Base;
use app\admin\service\box\BlindboxBoxesService;
use app\admin\service\box\BlindboxGoodsService;

class BlindboxBoxes extends Base
{
    /**
     * 箱子列表
     */
    public function index(BlindboxBoxesService $blindboxBoxesService)
    {
        return json($blindboxBoxesService->getBoxesList(input('param.')));
    }

    /**
     * 补箱
     */
    public function incBoxes(BlindboxGoodsService $blindboxGoodsService)
    {
        return json($blindboxGoodsService->makeBlindboxGoodsBox(input('post.')));
    }
}