<?php

namespace app\api\service\order;

use app\model\marketing\Coupon;
use app\model\marketing\Invite;
use app\model\marketing\UserCoupon;
use app\model\order\Order;
use app\model\user\User;
use app\model\user\UserBalanceChangeLog;
use app\model\user\UserLevel;
use app\model\user\UserLevelPresent;
use strategy\reward\RewardProvider;
use think\facade\Log;

class AfterOrderService
{
    public function run($orderInfo)
    {
        // 处理用户等级
        $res = $this->upLevel($orderInfo);
        if ($res['code'] != 0) {
            Log::error('----> 购买后用户升级有误: ' . $res['msg']);
        }

        // 维护优惠券
        $this->dealCoupon($orderInfo);

        // 处理邀新赠送
        $this->dealInvite($orderInfo);

        // 处理分佣问题
        $this->commission($orderInfo);
    }

    /**
     * 检测用户登记是否可以增长
     * @param $orderInfo
     * @return array
     */
    protected function upLevel($orderInfo)
    {
        // 等级是否开启
        $baseConfig = getConfByType('sys_base');
        $levelOpen = $baseConfig['level_open'];
        if ($levelOpen == 2) {
            return dataReturn(-1, '尚未开启用户等级');
        }

        // 查询当前用户等级
        $userModel = new User();
        $userInfo = $userModel->findOne(['id' => $orderInfo['user_id']])['data'];
        if (empty($userInfo)) {
            return dataReturn(-2, '用户信息有误');
        }

        // 查询下一个等级
        $ratio = $baseConfig['experience_ratio'];
        $giveExperience = ceil($orderInfo['pay_price'] * $ratio);

        $totalExperience = $userInfo['experience'] + $giveExperience;

        // 确定本次可以升级的等级
        $userLevelModel = new UserLevel();
        $levelInfo = $userLevelModel->findOne(['id' => $userInfo['level_id'], 'is_delete' => 1])['data'];
        $level = 0;
        if (!empty($levelInfo)) {
            $level = $levelInfo['level'];
        }

        $where[] = ['experience', '<=', $totalExperience];
        $where[] = ['level', '>', $level];
        $where[] = ['is_delete', '=', 1];
        $levelInfo = $userLevelModel->getAllList($where, '*', 'level asc')['data']->toArray();
        if (empty($levelInfo)) {
            // 维护经验值和等级
            $userModel->updateById([
                'experience' => $totalExperience,
                'update_time' => now()
            ], $userInfo['id']);

            return dataReturn(0, '本次无需升级');
        }

        // 处理升级赠送
        $finalLevel = 0;
        foreach ($levelInfo as $vo) {
            $finalLevel = $vo['id'];
            $this->givePresent($vo['id'], $orderInfo['user_id'], $orderInfo['user_name']);
        }

        // 维护经验值和等级
        $userModel->updateById([
            'level_id' => $finalLevel,
            'experience' => $totalExperience,
            'update_time' => now()
        ], $userInfo['id']);

        return dataReturn(0);
    }

    protected function givePresent($levelId, $userId, $userName)
    {
        $present = (new UserLevelPresent())->getAllList(['level_id' => $levelId])['data'];
        if (empty($present)) {
            return dataReturn(-10, '该等级不赠送');
        }

        $couponModel = new Coupon();
        $userCouponModel = new UserCoupon();

        foreach ($present as $vo) {
            if ($vo['present_num'] <= 0) {
                continue;
            }

            // 优惠券
            if ($vo['type'] == 1) {
                // 优惠券信息
                $couponInfo = $couponModel->where('id', $vo['present_id'])->find();
                if (empty($couponInfo)) {
                    Log::error('升级赠送的优惠券配置有误: ID-' . $vo['present_id']);
                    continue;
                }

                // 固定日期
                if ($couponInfo['validity_type'] == 1) {
                    $startTime = $couponInfo['start_time'];
                    $endTime = $couponInfo['end_time'];
                } else { // 领取之后
                    $startTime = now();
                    $endTime = date('Y-m-d H:i:s', strtotime('+' . $couponInfo['receive_useful_day'] . 'days'));
                }

                for ($i = 0; $i < $vo['present_num']; $i++) {
                    $userCouponModel->insert([
                        'source' => 1,
                        'code' => uniqid(),
                        'id_code' =>  uniqid(),
                        'coupon_id' => $vo['present_id'],
                        'coupon_name' => $couponInfo['name'],
                        'order_id' => 0,
                        'user_id' => $userId,
                        'username' => $userName,
                        'status' => 1,
                        'start_time' => $startTime,
                        'end_time' => $endTime,
                        'create_time' => now()
                    ]);
                }

            }
        }
    }

    /**
     * 消耗优惠券
     * @param $orderInfo
     * @return void
     */
    protected function dealCoupon($orderInfo)
    {
        if (!empty($orderInfo['coupon_code'])) {
            (new UserCoupon())->where('id_code', $orderInfo['coupon_code'])->update([
                'status' => 2,
                'order_id' => $orderInfo['id'],
                'update_time' => now()
            ]);
        }
    }

    /**
     * 处理邀新赠送
     * @param $orderInfo
     * @return array
     */
    protected function dealInvite($orderInfo)
    {
        $inviteModel = new Invite();
        $inviteInfo = $inviteModel->findOne(['id' => 1])['data'];
        if (empty($inviteInfo)) {
            return dataReturn(0);
        }

        $userModel = new User();
        $userInfo = $userModel->findOne(['id' => $orderInfo['user_id']])['data'];

        $userOrderNum = (new Order())->where('user_id', $orderInfo['user_id'])->where('pay_status', 2)->count('id');
        // 给推广者首次订单奖励
        if ($userOrderNum == 1 && !empty($inviteInfo['first_buy_send'])) {
            $firstBuySend = json_decode($inviteInfo['first_buy_send'], true);

            if (!empty($userInfo['parent_id'])) {
                // 赠送信息
                foreach ($firstBuySend as $reward) {

                    $provider = new RewardProvider($reward['type']);
                    $status = 5;
                    if ($reward['type'] == 'balance') {
                        $status = 6;
                    }

                    $provider->getStrategy()->receive($reward, [
                        'groupId' => -1,
                        'rewardId' => -1,
                        'user_id' => $userInfo['parent_id'],
                        'username' => $userInfo['nickname'],
                    ], $status);
                }
            }
        }

        // 返佣
        $limitDays = $inviteInfo['send_valid_days'];
        $validTime = strtotime($userInfo['create_time']) + $limitDays * 86400;
        $parentId = $userInfo['parent_id'];
        $userModel = new User();
        $userInfo = $userModel->where('id', $parentId)->lock(true)->find();

        // 有效期内
        if ($validTime > time() && !empty($userInfo)) {
            $balance = round($orderInfo['pay_price'] * $inviteInfo['rebate_ratio'], 2);
            (new UserBalanceChangeLog())->insert([
                'user_id' => $parentId,
                'username' => $userInfo['nickname'],
                'balance' => $balance,
                'type' => 7, // 好友购物返佣
                'recharge_id' => $orderInfo['id'],
                'create_time' => now()
            ]);

            // 增加余额
            $userModel->where('id', $parentId)->inc('balance', $balance)->update();
        }

        return dataReturn(0);
    }

    public function commission($orderInfo)
    {
        // 查询是否拥有上级
        $userModel = new User();
        $userInfo = $userModel->findOne(['id' => $orderInfo['user_id']])['data'];
        if (empty($userInfo['parent_id'])) {
            return;
        }
        // 读取分佣配置
        $ratioArr = getConfByType('sys_base');
        $tmpArr = [];
        for($i=1;$i<=2;$i++){
            $userInfoTmp = $userModel->findOne(['id' => $userInfo['parent_id']])['data'];
            if (empty($userInfo['parent_id'])) {
                continue;
            }
            $tmpArr[$i] = [
                'id'=> $userInfoTmp['id'],
                'nickname'=>$userInfoTmp['nickname'],
                'is_promotion'=>$userInfoTmp['is_promotion']
            ];
            $userInfo['parent_id'] = $userInfoTmp['parent_id'];
        }
        $data = [];
        if ($tmpArr[1]['is_promotion'] && !empty($ratioArr['one_promotion']) && $tmpArr[1]['id']) {
            $balance = round($orderInfo['pay_price'] * rtrim($ratioArr['one_promotion'],'%') / 100,2);
            $data[] = [
                'user_id' => $tmpArr[1]['id'],
                'username' => $tmpArr[1]['nickname'],
                'balance' => $balance,
                'type' => 10, // 分佣
                'order_id' => $orderInfo['id'],
                'create_time' => now()
            ];
            $userModel->where('id', $tmpArr[1]['id'])->inc('balance', $balance)->update();
        }
        // 上上级开启分佣 并且配置分佣
        if ($tmpArr[2]['is_promotion'] && !empty($ratioArr['one_promotion']) && $tmpArr[2]['id']) {
            $balance = round($orderInfo['pay_price'] * rtrim($ratioArr['two_promotion'],'%') / 100,2);
            $data[] = [
                'user_id' => $tmpArr[2]['id'],
                'username' => $tmpArr[2]['nickname'],
                'balance' => $balance,
                'type' => 10, // 分佣
                'order_id' => $orderInfo['id'],
                'create_time' => now()
            ];
            $userModel->where('id', $tmpArr[2]['id'])->inc('balance', $balance)->update();
        }
        $data && (new UserBalanceChangeLog())->insertAll($data);
    }
}
