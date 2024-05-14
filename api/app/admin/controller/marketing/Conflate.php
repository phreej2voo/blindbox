<?php
namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\ConflateService;

class Conflate extends Base
{
    /**
     * 合成列表
     */
    public function index(ConflateService $conflateService)
    {
        return json($conflateService->getConflateList(input('param.')));
    }

    /**
     * 添加合成
     */
    public function add(ConflateService $conflateService)
    {
        return json($conflateService->addConflate(input('post.')));
    }

    /**
     * 编辑合成
     */
    public function edit(ConflateService $conflateService)
    {
        return json($conflateService->editConflate(input('post.')));
    }

    /**
     * 删除合成
     */
    public function del(ConflateService $conflateService)
    {
        return json($conflateService->delConflate(input('param.id')));
    }

    /**
     * 合成记录
     */
    public function log(ConflateService $conflateService)
    {
        return json($conflateService->getConflateLog(input('param.')));
    }
}