<?php

namespace app\api\controller\v1\marketing;

use app\api\controller\Base;
use app\api\service\marketing\InviteService;

class Invite extends Base
{
    /**
     * 获取邀请信息
     */
    public function getInfo(InviteService $inviteService)
    {
        return json($inviteService->getInviteInfo());
    }

    /**
     * 获取我的奖励
     */
    public function getMyReward(InviteService $inviteService)
    {
        return json($inviteService->getMyReward(input('param.')));
    }

    /**
     * 获取我的朋友
     */
    public function getMyFriend(InviteService $inviteService)
    {
        return json($inviteService->getMyFriend(input('param.')));
    }

    /**
     * 获取我的分享二维码
     */
    public function getQrcode(InviteService $inviteService)
    {
        return json($inviteService->getShareQrCode(input('post.')));
    }
}