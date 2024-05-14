<?php

namespace app\admin\controller\box;

use app\admin\controller\Base;
use app\admin\service\box\BlindboxGoodsService;

class BlindboxGoods extends Base
{
    /**
     * 盲盒详情列表
     */
    public function index()
    {
        $blindboxGoodsService = new BlindboxGoodsService();
        return json($blindboxGoodsService->getBlindboxGoodslList(input('param.blindbox_id')));
    }

    /**
     * 添加盲盒商品
     */
    public function add()
    {
        $blindboxGoodsService = new BlindboxGoodsService();
        return json($blindboxGoodsService->addBlindboxGoods(input('post.')));
    }


    /**
     * 修改赏种
     * @return \think\response\Json
     */
    public function edit()
    {
        $blindboxGoodsService = new BlindboxGoodsService();
        return json($blindboxGoodsService->editBlindboxGoods(input('post.')));
    }

    /**
     * 生成箱子
     */
    public function box()
    {
        $blindboxGoodsService = new BlindboxGoodsService();
        return json($blindboxGoodsService->makeBlindboxGoodsBox(input('post.')));
    }
}
