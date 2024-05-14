<?php

namespace app\admin\service\marketing;

use app\model\marketing\Invite;
use app\model\marketing\InviteReward;
use app\model\user\User;

class InviteService
{
    /**
     * 获取设置信息
     * @return array
     */
    public function getInviteInfo()
    {
        $info = (new Invite())->findOne(['id' => 1]);

        if (!empty($info)) {
            if (!empty($info['data']['reg_send'])) {
                $info['data']['reg_send'] = json_decode($info['data']['reg_send'], true);
            }

            if (!empty($info['data']['first_buy_send'])) {
                $info['data']['first_buy_send'] = json_decode($info['data']['first_buy_send'], true);
            }
        }

        return $info;
    }

    /**
     * 设置邀请信息
     * @param $param
     * @return array
     */
    public function setInviteInfo($param)
    {
        $inviteModel = new Invite();
        $inviteInfo = $inviteModel->findOne(['id' => 1])['data'];

        $param['reg_send'] = json_encode($param['reg_send']);
        $param['first_buy_send']= json_encode($param['first_buy_send']);
        if (!empty($inviteInfo)) {
            $param['update_time'] = now();

            return $inviteModel->updateById($param, $inviteInfo['id']);
        } else {
            $param['create_time'] = now();

            return $inviteModel->insertOne($param);
        }
    }

    /**
     * 邀请记录
     * @param $param
     * @return array
     */
    public function getInviteLog($param)
    {
        $where[] = ['reward_type', '=', 1];
        $userModel = new User();

        if (!empty($param['give_user_name'])) {
            $list = $userModel->getAllList([
                ['nickname', 'like', '%' . $param['give_user_name'] . '%']
            ], 'id')['data'];
            $uIds = [];
            foreach ($list as $vo) {
                $uIds[] = $vo['id'];
            }

            $where[] = ['give_user_id', 'in', $uIds];
        }

        if (!empty($param['from_user_name'])) {
            $list = $userModel->getAllList([
                ['nickname', 'like', '%' . $param['give_user_name'] . '%']
            ], 'id')['data'];
            $uIds = [];
            foreach ($list as $vo) {
                $uIds[] = $vo['id'];
            }

            $where[] = ['from_user_id', 'in', $uIds];
        }

        if (!empty($param['create_time'])) {
            $where[] = ['create_time', '>=', $param['create_time'][0] . ' 00:00:00'];
            $where[] = ['create_time', '<=', $param['create_time'][1] . ' 23:59:59'];
        }

        $list = (new InviteReward())->where($where)->paginate($param['limit']);

        $inviteIds = [];
        $regUserIds = [];
        $list->each(function ($item) use (&$inviteIds, &$regUserIds) {
            $inviteIds[] = $item->give_user_id;
            $regUserIds[] = $item->from_user_id;
        });

        $inviteUserList = $userModel->getAllList([
            ['id', 'in', array_unique($inviteIds)]
        ], 'id,nickname')['data'];
        $inviteId2Name = [];
        foreach ($inviteUserList as $vo) {
            $inviteId2Name[$vo['id']] = $vo['nickname'];
        }

        $regUserList = $userModel->getAllList([
            ['id', 'in', array_unique($regUserIds)]
        ], 'id,nickname')['data'];
        $regId2Name = [];
        foreach ($regUserList as $vo) {
            $regId2Name[$vo['id']] = $vo['nickname'];
        }

        // 补全
        $list->each(function ($item) use ($inviteId2Name, $regId2Name) {
            if (isset($inviteId2Name[$item->give_user_id])) {
                $item->give_user_name = $inviteId2Name[$item->give_user_id];
            }

            if (isset($regId2Name[$item->from_user_id])) {
                $item->from_user_name = $regId2Name[$item->from_user_id];
            }

            return $item;
        });

        return dataReturn(0, 'success', $list);
    }
}