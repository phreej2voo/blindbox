<?php

namespace app\admin\controller\box;

use app\admin\controller\Base;
use app\admin\service\box\BlindboxTagService;

class BlindboxTag extends Base
{
    /**
     * 盲盒标签列表
     */
    public function index()
    {
        $blindboxTagService = new BlindboxTagService();
        return json($blindboxTagService->getBlindboxTagList(input('param.')));
    }

    /**
     * 获取盲盒标签列表
     * @return \think\response\Json
     */
    public function list()
    {
        return json((new BlindboxTagService())->selectBlindboxTagList());
    }

    /**
     * 添加标签
     */
    public function add()
    {
        $blindboxTagService = new BlindboxTagService();
        return json($blindboxTagService->addBlindboxTag(input('post.')));
    }

    /**
     * 编辑标签
     */
    public function edit()
    {
        $blindboxTagService = new BlindboxTagService();
        return json($blindboxTagService->editBlindboxTag(input('post.')));
    }

    /**
     * 删除标签
     */
    public function del()
    {
        $blindboxTagService = new BlindboxTagService();
        return json($blindboxTagService->delBlindboxTag(input('param.id')));
    }
}