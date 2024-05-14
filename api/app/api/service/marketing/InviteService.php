<?php

namespace app\api\service\marketing;

use app\model\marketing\Invite;
use app\model\marketing\InviteReward;
use app\model\user\User;
use EasyWeChat\Factory;
use EasyWeChat\Kernel\Http\StreamResponse;
use think\facade\App;

class InviteService
{
    /**
     * 获取邀请信息
     * @return array
     */
    public function getInviteInfo()
    {
        $inviteInfo = (new Invite())->findOne(['id' => 1])['data'];
        if (empty($inviteInfo)) {
            return dataReturn(-10, '暂无邀新数据');
        }

        if (!empty($inviteInfo['reg_send'])) {
            $inviteInfo['reg_send'] = json_decode($inviteInfo['reg_send'], true);
        }

        if (!empty($inviteInfo['first_buy_send'])) {
            $inviteInfo['first_buy_send'] = json_decode($inviteInfo['first_buy_send'], true);
        }

        return dataReturn(0, 'success', $inviteInfo);
    }

    /**
     * 获取我的推广奖励
     * @param $param
     * @return array
     */
    public function getMyReward($param)
    {
        $userInfo = getUserInfo();

        $list = (new InviteReward())->getPageList($param['limit'], [
            'give_user_id' => $userInfo['id']
        ])['data'];

        $list->each(function ($item) {
           $item->reward_content = json_decode($item->reward_content, true);
           return $item;
        });

        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取我的朋友
     * @param $param
     * @return array
     */
    public function getMyFriend($param)
    {
        $userInfo = getUserInfo();

        $list = (new User())->getPageList($param['limit'], [
            'parent_id' => $userInfo['id']
        ], 'id,nickname,avatar')['data'];

        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取我的分享二维码
     * @param $param
     * @return array
     */
    public function getShareQrCode($param)
    {
        if (empty($param['path'])) {
            return dataReturn(-5, '路径不能为空');
        }

        $miniConfig = getConfByType('miniapp');
        $config = [
            'app_id' => $miniConfig['miniapp_app_id'],
            'secret' => $miniConfig['miniapp_app_secret']
        ];
        $app = Factory::miniProgram($config);

        $userInfo = getUserInfo();
        $scene = 'spId=' . shareCode($userInfo['id']) . '&bId=' . $param['blindbox_id'];
        $response = $app->app_code->getUnlimit($scene, [
            'page'  => $param['path'],
            'width' => 220
        ]);

        if ($response instanceof StreamResponse) {
            $qrcodeSrc = App::getRootPath() . 'public' . DIRECTORY_SEPARATOR . 'qrcode';
            $fileName = md5($scene) . '.png';
            $response->save($qrcodeSrc, $fileName);
        } else {
            return dataReturn(-6, '生成错误', $response);
        }

        return dataReturn(0, 'success', request()->domain() . '/qrcode/' . $fileName);
    }
}