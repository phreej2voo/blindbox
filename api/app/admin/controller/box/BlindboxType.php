<?php

namespace app\admin\controller\box;

use app\admin\controller\Base;
use app\admin\service\box\BlindboxTypeService;

class BlindboxType extends Base
{
    /**
     * @var $blindboxTagService
     */
    protected $blindboxTagService;

    public function initialize()
    {
        parent::initialize();
        $this->blindboxTagService = new BlindboxTypeService();
    }

    /**
     * 盲盒分类列表
     */
    public function index()
    {
        // $blindboxTagService = new BlindboxTypeService();
        return json($this->blindboxTagService->getBlindboxTypeList(input('param.')));
    }

    /**
     * 获取盲盒分类列表
     * @return \think\response\Json
     */
    public function list()
    {
        return json($this->blindboxTagService->selectBlindboxTypeList());
    }

    /**
     * 添加分类
     */
    public function add()
    {
        // $blindboxTagService = new BlindboxTypeService();
        return json($this->blindboxTagService->addBlindboxType(input('post.')));
    }

    /**
     * 编辑分类
     */
    public function edit()
    {
        // $blindboxTagService = new BlindboxTypeService();
        return json($this->blindboxTagService->editBlindboxType(input('post.')));
    }

    /**
     * 删除分类
     */
    public function del()
    {
        //$blindboxTagService = new BlindboxTypeService();
        return json($this->blindboxTagService->delBlindboxType(input('param.id')));
    }
}
