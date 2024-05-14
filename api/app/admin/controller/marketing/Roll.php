<?php

namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\RollService;

class Roll extends Base
{
    /**
     * 福利房列表
     */
    public function index(RollService $rollService)
    {
        return json($rollService->getRollList(input('param.')));
    }

    /**
     * 添加福利房
     */
    public function add(RollService $rollService)
    {
        return json($rollService->addRoll(input('post.')));
    }

    /**
     * 编辑福利房
     */
    public function edit(RollService $rollService)
    {
        if (request()->isPost()) {
            return json($rollService->editRoll(input('post.')));
        }

        return json($rollService->getRollDetail(input('param.id')));
    }

    /**
     * 关闭福利房
     */
    public function del(RollService $rollService)
    {
        return json($rollService->delRoll(input('param.id')));
    }

    /**
     * 参与详情
     */
    public function detail(RollService $rollService)
    {
        return json($rollService->getJoinDetail(input('param.id')));
    }
}