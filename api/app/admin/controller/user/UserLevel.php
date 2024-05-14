<?php

namespace app\admin\controller\user;

use app\admin\controller\Base;
use app\admin\service\user\UserLevelService;

class UserLevel extends Base
{
    /**
     * 用户等级列表
     */
    public function index()
    {
        $userLevelService = new UserLevelService();
        return json($userLevelService->getList(input('param.')));
    }

    /**
     * 添加用户等级
     */
    public function add()
    {
        $userLevelService = new UserLevelService();
        return json($userLevelService->addLevel(input('post.')));
    }

    /**
     * 编辑用户等级
     */
    public function edit()
    {
        $userLevelService = new UserLevelService();
        return json($userLevelService->editLevel(input('post.')));
    }

    /**
     * 删除用户等级
     */
    public function del()
    {
        $userLevelService = new UserLevelService();
        return json($userLevelService->delLevel(input('param.id')));
    }
}