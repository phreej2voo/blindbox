<?php

namespace app\api\controller\v1\user;

use app\admin\service\user\UserLevelService;
use app\api\controller\Base;
use app\api\service\user\UserService;

class User extends Base
{
    protected $userInfo;

    public function initialize()
    {
        parent::initialize();
        $this->userInfo = getJWT(getHeaderToken());
    }

    /**
     * 获取用户信息
     */
    public function getUserInfo()
    {
        $userService = new UserService();
        return json($userService->getUserInfo());
    }

    /**
     * 设置用户信息
     */
    public function setUserInfo()
    {
        $postParam = request()->post();
        $postParam['user_info'] = $this->userInfo;
        $userService = new UserService();
        return json($userService->setUserInfo($postParam));
    }

    // 修改生日
    public function setBirthday()
    {
        $postParam = request()->post();
        $postParam['user_info'] = $this->userInfo;
        $userService = new UserService();
        return json($userService->setBirthday($postParam));
    }

    /**
     * 用户开盒记录
     */
    public function orderRecordLog()
    {
        $userService = new UserService();
        $param = \request()->get();
        $param['user_id'] = $this->userInfo['id'] ?? '';
        return json($userService->orderRecordLog($param));
    }

    /**
     * 我的订单列表
     */
    public function orderList()
    {
        $userService = new UserService();
        $param = \request()->get();
        $param['user_id'] = $this->userInfo['id'] ?? '';
        return json($userService->orderList($param));
    }

    /**
     * 等级信息
     */
    public function levelInfo()
    {
        $param = input('param.');
        $param['user_id'] = getUserInfo();

        $userLevelService = new UserLevelService();
        return json($userLevelService->getLevelInfo($param));
    }

    /**
     * 是否开启用户等级
     */
    public function levelStatus()
    {
        // 等级是否开启
        $baseConfig = getConfByType('sys_base');
        $levelOpen = $baseConfig['level_open'];
        if ($levelOpen == 2) {
            return jsonReturn(-1, '尚未开启用户等级');
        }

        return jsonReturn(0);
    }

    /**
     * 修改密码
     */
    public function reset()
    {
        $userService = new UserService();
        return json($userService->resetPassword(input('post.')));
    }
}
