<?php
namespace app\api\service\marketing;

use app\model\marketing\VipCard;
use app\model\marketing\VipCardOrder;
use app\model\marketing\VipCardReceived;
use app\model\marketing\VipCardUser;
use strategy\pay\PayProvider;
use strategy\reward\RewardProvider;
use think\facade\Db;
use think\facade\Log;
use utils\Tools;

class VipCardService
{
    /**
     * 获取会员卡列表
     * @param $param
     * @return array
     */
    public function getCardList($param)
    {
        $where[] = ['type', '=', $param['type']];
        $where[] = ['status', '=', 1];

        return (new VipCard())->getPageList($param['limit'], $where, 'id,type,title,price,stock,description');
    }

    /**
     * 创建订单
     * @param $param
     * @return array
     */
    public function createOrder($param)
    {
        if (empty($param['card_id']) || empty($param['pay_way'] || empty($param['platform']))) {
            return dataReturn(-1, '支付参数有误');
        }

        Db::startTrans();
        try {

            $userInfo = getUserInfo();

            // 会员卡信息
            $info = (new VipCard())->where('id', $param['card_id'])->where('status', 1)->find();
            if (empty($info)) {
                throw new \Exception('会员卡信息错误');
            }

            if ($info['stock'] != -1 && $info['stock'] == 0) {
                throw new \Exception('会员卡库存不足');
            }

            $vipCardModel = new VipCardUser();
            if ($info['one_limit'] != -1) {
                $num = $vipCardModel->field('id')->where('card_id', $info['id'])->where('user_id', $userInfo['id'])->count('id');
                if ($num >= $info['one_limit']) {
                    throw new \Exception('该会员卡最多购买' . $info['one_limit'] . '次，您已无法购买');
                }
            }

            // 关闭过期的会员卡
            $vipCardModel->where('user_id', $userInfo['id'])->where('status', 1)
                ->where('end_date', '<', date('Y-m-d'))->update([
                    'status' => 2,
                    'update_time' => now()
                ]);

            // 是否有正在进行的会员卡
            $has = $vipCardModel->field('id')->where('user_id', $userInfo['id'])->where('status', 1)->find();
            if (!empty($has)) {
                throw new \Exception('您有尚未过期的会员卡，不可再次购买');
            }

            // 创建订单
            $oderNo = makeOrderNo('C');
            $orderId = (new VipCardOrder())->insertGetId([
                'order_no' => $oderNo,
                'card_id' => $info['id'],
                'card_type' => $info['type'],
                'pay_way' => $param['pay_way'],
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'price' => $info['price'],
                'status' => 1, // 待支付
                'create_time' => now()
            ]);

            if ($param['pay_way'] == 4) {
                $param['pay_way'] = 41;
            }

            // 拼装参数执行支付
            $payProvider = (new PayProvider($param['pay_way']))->getStrategy();
            $urlConfig = getConfByType('api_url');
            $param['host'] = $urlConfig['api_url'];

            $orderParam = [
                'order_id' => $orderId,
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'pay_price' => $info['price'],
                'host' => $param['host'],
                'pay_order_no' => $oderNo,
                'subject' => '会员卡后买',
                'return_url' => $urlConfig['h5_web_url'] . '/#/plugins/member-benefits/claim-benefits/claim-benefits'
            ];

            $res = Tools::payByPlatForm($payProvider, $param['platform'] , $orderParam);
            Db::commit();
            return $res;
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('购买会员卡失败: ' . formatErrMsg($e));
            return dataReturn(-4, $e->getMessage());
        }
    }

    /**
     * 获取我的待领取权限
     * @return array
     */
    public function getMyEquity()
    {
        $userInfo = getUserInfo();

        $vipUserModel = new VipCardUser();
        $vipUserModel->where('user_id', $userInfo['id'])->where('status', 1)
            ->where('end_date', '<', date('Y-m-d'))->update([
                'status' => 2,
                'update_time' => now()
            ]);

        // 会员权限
        $vipInfo = $vipUserModel->with(['card'])->where([
            'user_id' => $userInfo['id'],
            'status' => 1
        ])->find();
        if (empty($vipInfo)) {
            return dataReturn(0, '暂无有效的会员卡');
        }

        // 领取记录
        $index = (new VipCardReceived())->where([
            'user_id' => $userInfo['id'],
            'receive_date' => date('Y-m-d')
        ])->column('award_index');

        $equity = json_decode($vipInfo['equity'], true);
        $day = floor((time() - strtotime($vipInfo['start_date'])) / 86400);
        $award = $equity[$day];

        foreach ($award['award'] as $key => $vo) {
            $award['award'][$key]['status'] = in_array($key, $index) ? 2 : 1;
        }

        $award['card'] = [
            'name' => (new VipCard())->findOne(['id' => $vipInfo['card_id']])['data']->title,
            'card_no' => $vipInfo['card_no'],
            'end_date' => $vipInfo['end_date'],
            'valid_day' => ceil((strtotime($vipInfo['end_date']) - time()) / 86400),
            'type' => $vipInfo['type']
        ];

        return dataReturn(0, 'success', $award);
    }

    /**
     * 执行领取
     * @param $param
     * @return array
     */
    public function doReceive($param)
    {
        if (!isset($param['index']) || $param['index'] < 0) {
            return dataReturn(-2, '请选择要领取的奖励');
        }

        Db::startTrans();
        try {

            // 是否领取过
            $userInfo = getUserInfo();
            $vipUserModel = new VipCardUser();

            // 领取记录
            $vipCardReceivedModel = new VipCardReceived();
            $index = $vipCardReceivedModel->where([
                'user_id' => $userInfo['id'],
                'receive_date' => date('Y-m-d')
            ])->column('award_index');

            if (in_array($param['index'], $index)) {
                throw new \Exception('该权益今日已经领取');
            }

            // 会员权限
            $vipInfo = $vipUserModel->with(['card'])->where([
                'user_id' => $userInfo['id'],
                'status' => 1
            ])->find();
            if (empty($vipInfo)) {
                throw new \Exception('暂无有效的会员卡');
            }

            $equity = json_decode($vipInfo['equity'], true);
            $day = floor((time() - strtotime($vipInfo['start_date'])) / 86400);
            $award = $equity[$day];

            // 领取的奖品
            $receiveAward = $award['award'][$param['index']];
            $provider = new RewardProvider($receiveAward['type']);
            $status = 5;
            if ($receiveAward['type'] == 'balance') {
                $status = 6;
            }

            $provider->getStrategy()->receive($receiveAward, [
                'groupId' => -1,
                'rewardId' => -1,
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
            ], $status);

            // 记录领取记录
            $vipCardReceivedModel->insert([
                'receive_date' => date('Y-m-d'),
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'award_index' => $param['index'],
                'award_type' => $receiveAward['type'],
                'resource_id' => $receiveAward['resourceId'],
                'num' => $receiveAward['num'],
                'amount' => $receiveAward['value'],
                'create_time' => now()
            ]);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-3, $e->getMessage());
        }

        return dataReturn(0, '领取成功');
    }

    /**
     * 领取记录
     * @param $param
     * @return array
     */
    public function getReceiveLog($param)
    {
        $userInfo = getUserInfo();

        $field = 'receive_date,username,award_type,num,amount,create_time';
        return (new VipCardReceived())->getPageList($param['limit'], ['user_id' => $userInfo['id']], $field);
    }
}
