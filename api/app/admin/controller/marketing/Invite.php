<?php

namespace app\admin\controller\marketing;

use app\admin\controller\Base;
use app\admin\service\marketing\InviteService;

class Invite extends Base
{
    /**
     * 邀请设置
     */
    public function set(InviteService  $inviteService)
    {
        if (request()->isPost()) {

            $param = input('post.');

            return json($inviteService->setInviteInfo($param));
        }

        return json($inviteService->getInviteInfo());
    }

    /**
     * 获取邀请记录
     */
    public function getInviteLog(InviteService $inviteService)
    {
        return json($inviteService->getInviteLog(input('param.')));
    }
}