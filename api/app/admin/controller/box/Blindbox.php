<?php

namespace app\admin\controller\box;

use app\admin\controller\Base;
use app\admin\service\box\BlindboxService;

class Blindbox extends Base
{
    /**
     * 盲盒列表
     */
    public function index()
    {
        $blindboxService = new BlindboxService();
        return json($blindboxService->getBlindboxList(input('param.')));
    }

    /**
     * 盲盒index_type
     * @return \think\response\Json
     */
    public function boxindextype()
    {
//        return jsonReturn(0,'success',['index_type'=>\app\model\box\Blindbox::INDEX_TYPE_ARR]);

                return jsonReturn(0,'success',['index_type'=>json_decode(getConfByType('sys_base')['index_config'],1)]);
    }
    /**
     * 添加盲盒
     */
    public function add()
    {
        $blindboxService = new BlindboxService();
        return json($blindboxService->addBlindbox(input('post.')));
    }

    /**
     * 编辑盲盒
     */
    public function edit()
    {
        $blindboxService = new BlindboxService();
        return json($blindboxService->editBlindbox(input('post.')));
    }

    /**
     * 删除盲盒
     */
    public function del()
    {
        $blindboxService = new BlindboxService();
        return json($blindboxService->delBlindbox(input('param.id')));
    }

    /**
     * 设置保底
     */
    public function guarantee()
    {
        $blindboxService = new BlindboxService();
        return json($blindboxService->setGuarantee(input('post.')));
    }
}
