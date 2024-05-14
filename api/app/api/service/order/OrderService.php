<?php

namespace app\api\service\order;

use app\api\validate\order\OrderValidate;
use app\api\validate\order\TrailValidate;
use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxBoxesConfig;
use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxGoods;
use app\model\box\BlindboxTag;
use app\model\goods\Goods;
use app\model\marketing\Coupon;
use app\model\marketing\UserCoupon;
use app\model\order\Order;
use app\model\order\OrderRecord;
use app\model\user\User;
use app\model\user\UserBoxDeliver;
use app\model\user\UserBoxDeliverDetail;
use app\model\user\UserIntegralChangeLog;
use strategy\express\ExpressProvider;
use strategy\lottery\LotteryProvider;
use strategy\pay\PayProvider;
use think\facade\Db;
use think\facade\Log;
use utils\CapitalChange;
use utils\LuaScript;
use utils\MyRedis;
use utils\Tools;

class OrderService
{
    /**
     * 试算订单试算
     * @param $param
     * @param $type
     * @return array
     */
    public function trail($param, $type = 1)
    {
        $trailValidate = new TrailValidate();
        if (!$trailValidate->check($param)) {
            return dataReturn(-1, $trailValidate->getError());
        }

        // 盲盒信息
        $blindboxModel = new Blindbox();
        $blindboxInfo = $blindboxModel->findOne(['id' => $param['blindbox_id']]);

        // 查询账户余额
        $userModel = new User();
        $userInfo = $userModel->findOne([
            ['id', '=', $param['user_info']['id']],
            ['status', '=', 1]
        ], 'balance,integral')['data'];

        if (empty($userInfo)) {
            return dataReturn(-1, '用户信息错误');
        }

        $totalPrice = round($blindboxInfo['data']['price'] * $param['num'], 2);
        // 如果使用了优惠券
        $couponAmount = 0;
        $canUseCoupon = false;
        if (!empty($param['coupon_code']) && $totalPrice > 0) {
            $userCoupon = (new UserCoupon())->findOne([
                'id_code' => $param['coupon_code'],
                'user_id' => $param['user_info']['id'],
                'status' => 1
            ])['data'];

            if (!empty($userCoupon)) {
                $couponInfo = (new Coupon())->findOne(['id' => $userCoupon['coupon_id']])['data'];
                $dealCoupon = [];
                // 有门槛
                if ($couponInfo['is_threshold'] == 1) {
                    if ($totalPrice >= $couponInfo['threshold_amount']) {
                        $canUseCoupon = true;
                        $dealCoupon = $this->dealCouponAmount($totalPrice, $couponInfo);
                    }
                } else { // 无门槛
                    $canUseCoupon = true;
                    $dealCoupon = $this->dealCouponAmount($totalPrice, $couponInfo);
                }

                if (!empty($dealCoupon)) {
                    $totalPrice = $dealCoupon['totalPrice'];
                    $couponAmount = $dealCoupon['couponAmount'];
                }
            }
        }

        // 查询可抵扣的哈希币
        $canUseIntegral = 0;
        $canDeductionAmount = 0;
        $ratio = getConfByType('sys_base')['change_ratio'];
        /*if (!empty($ratio) && $userInfo['integral'] > 0 && $param['use_integral'] == 1) {
            // 可抵扣金额
            $canDeductionAmount = round($userInfo['integral'] / $ratio, 2);

            if ($canDeductionAmount > $totalPrice) {
                $canUseIntegral = round($totalPrice * $ratio, 2);
                $canDeductionAmount = $totalPrice;
                $totalPrice = 0;
            } else {
                $totalPrice = $totalPrice - $canDeductionAmount;
                $canUseIntegral = $userInfo['integral'];
            }
        }*/

        $trailData = [
            'blindbox_img' => $blindboxInfo['data']['pic'],
            'price' => $blindboxInfo['data']['price'],
            'num' => $param['num'],
            'total_price' => round($totalPrice, 2),
            'balance' => $userInfo['balance'],
            'canUseIntegral' => $canUseIntegral,
            'canDeductionAmount' => $canDeductionAmount,
            'couponAmount' => $couponAmount, // 优惠券优惠金额
            'canUseCoupon' => $canUseCoupon
        ];

        if ($type == 2) {
            $trailData['ratio'] = $ratio;
        }

        return dataReturn(0, 'success', $trailData);
    }

    /**
     * 优惠券金额
     * @param $totalPrice
     * @param $couponInfo
     * @return array
     */
    protected function dealCouponAmount($totalPrice, $couponInfo)
    {
        // 满减券
        if ($couponInfo['type'] == 1) {
            $discountAmount = $couponInfo['amount'];
        } else { // 折扣券
            $discountAmount = round($totalPrice - $totalPrice * $couponInfo['discount'], 2); // 优惠金额
            if ($discountAmount > $couponInfo['max_discount_amount'] && $couponInfo['max_discount_amount'] > 0) {
                $discountAmount = $couponInfo['max_discount_amount'];
            }

            if ($discountAmount > $totalPrice) {
                $discountAmount = $totalPrice;
            }
        }

        $totalPrice = $totalPrice - $discountAmount;
        $totalPrice < 0 && $totalPrice = 0;

        if ($discountAmount > $totalPrice) {
            $couponAmount = $totalPrice;
        } else {
            $couponAmount = $discountAmount;
        }

        return compact('totalPrice', 'couponAmount');
    }

    /**
     * 创建的订单
     */
    public function createOrder($param)
    {
        $trailValidate = new OrderValidate();
        if (!$trailValidate->check($param)) {
            return dataReturn(-1, $trailValidate->getError());
        }

        if ($param['num'] == 0) {
            return dataReturn(-7, '暂无库存');
        }

        $blindboxModel = new Blindbox();
        $blindboxInfo = $blindboxModel->findOne(['id' => $param['blindbox_id']])['data'];
//        if (!in_array($param['play_id'],[4,5])) {
//            return dataReturn(-3, '盲盒玩法异常');
//        }
//        if ($param['play_id'] != $blindboxInfo['play_id']) {
//            return dataReturn(-3, '盲盒玩法异常');
//        }

        $trailData = $this->trail($param, 2);
        if ($trailData['code'] != 0) {
            return $trailData;
        }

        $hasStock = $this->checkStock($param);
        if (!$hasStock) {
            $hasStock = (new BlindboxBoxes())->field('stock')->where('blindbox_id', $param['blindbox_id'])->where('box_no', $param['box_id'])->find();
            if (empty($hasStock)) {
                return dataReturn(-5, '暂无库存，已售罄');
            }
            if ($hasStock['stock'] > 0) {
                return dataReturn(-5, '前面有人正在排队支付...');
            }

            return dataReturn(-5, '暂无库存');
        }

        $orderNo = makeOrderNo('B');
        $payOrderNo = makeOrderNo('B');
        $postage = 0;

        $orderModel = new Order();
        $res = $orderModel->insertOne([
            'pid' => 0,
            'type' => 2,
            'order_no' => $orderNo,
            'pay_order_no' => $payOrderNo,
            'user_id' => $param['user_info']['id'],
            'user_name' => $param['user_info']['nickname'],
            'blindbox_id' => $param['blindbox_id'],
            'box_id' => $param['box_id'],
            'play_id' => $blindboxInfo['play_id'],
            'total_num' => $trailData['data']['num'],
            'unit_price' => $blindboxInfo['price'],
            'postage' => $postage,
            'order_price' => $trailData['data']['total_price'],
            'pay_way' => $param['pay_way'],
            'pay_price' => $trailData['data']['total_price'] + $postage,
            'pay_integral' => $trailData['data']['canUseIntegral'],
            'coupon_code' => $param['coupon_code'], // 优惠券id
            'coupon_amount' => $trailData['data']['couponAmount'], // 优惠券抵扣金额
            'pay_status' => 1,
            'status' => 1,
            'integral_ratio' => $trailData['data']['ratio'],
            'create_time' => date('Y-m-d H:i:s')
        ]);
        if ($res['code'] != 0) {
            return $res;
        }

        // 写入延时队列
        // 支付方式 1:微信 2:支付宝 3:哈希币 4:余额
        if (in_array($param['pay_way'], config('pay.need_notify'))) {
            $redisObj = (new MyRedis())->getObject();
            Tools::redisQueueSend($redisObj, config('redis.queue_name'), [
                'order_id' => $res['data'],
                'blindbox_id' => $param['blindbox_id'],
                'box_id' => $param['box_id'],
                'num' => $trailData['data']['num']
            ], config('shop.order_time'));
        }

        return dataReturn(0, '创建成功', [
            'order_no' => $payOrderNo
        ]);
    }

    /**
     * 发起支付
     */
    public function payOrder($param)
    {
        if (empty($param['order_no']) || empty($param['platform'])) {
            return dataReturn(-1, '参数错误');
        }

        $urlConfig = getConfByType('api_url');
        $param['host'] = $urlConfig['api_url'];

        // 验证订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->findOne([
            'pay_order_no' => $param['order_no'],
            'status' => 1,
            'user_id'=>$param['user_info']['id']
        ])['data'];
        if (empty($orderInfo)) {
            return dataReturn(-1, '订单信息错误');
        }

        $userInfo = (new User())->findOne(['id' => $orderInfo['user_id']], 'avatar')['data'];

        $orderParam = [
            'id' => $orderInfo['id'],
            'total_num' => $orderInfo['total_num'],
            'unit_price' => $orderInfo['unit_price'],
            'order_no' => $orderInfo['order_no'],
            'trace_id' => 0,
            'blindbox_id' => $orderInfo['blindbox_id'],
            'box_id' => $orderInfo['box_id'],
            'user_id' => $orderInfo['user_id'],
            'user_name' => $orderInfo['user_name'],
            'user_avatar' => $userInfo['avatar'],
            'pay_price' => $orderInfo['pay_price'],
            'pay_integral' => $orderInfo['pay_integral'],
            'coupon_code' => $orderInfo['coupon_code'],
            'host' => $param['host'],
            'play_id' => $orderInfo['play_id'],
            'pay_order_no' => $orderInfo['pay_order_no'],
            'subject' => '盲盒购买' . $orderInfo['total_num'] . '个'
        ];

        if ($orderInfo['play_id'] == 1) {
            $orderParam['return_url'] = $urlConfig['h5_web_url'] . '/#/plugins/yfs-detail/index?openReward=true&order_no=' . $orderInfo['pay_order_no'];
        } else if ($orderInfo['play_id'] == 2) {
            $orderParam['return_url'] = $urlConfig['h5_web_url'] . '/#/plugins/hash-detail/index?openReward=true&order_no=' . $orderInfo['pay_order_no'];
        }


        // 无需实际支付
        if ($orderInfo['pay_price'] == 0) {
//            直接完成订单
            return $this->completeOrder($orderParam);
        }

        // 拼装参数执行支付
        $payProvider = (new PayProvider($orderInfo['pay_way']))->getStrategy();
        $res = Tools::payByPlatForm($payProvider, $param['platform'] , $orderParam);

        // 余额支付
        if ($orderInfo['pay_way'] == 4) {
            return $res;
        }

        return dataReturn(201, '盲盒订单支付', $res);
    }

    /**
     * 获取订单结果
     * @param $param
     * @return array
     */
    public function getResult($param)
    {
        $userInfo = getJWT(getHeaderToken());

        $orderModel = new Order();
        $orderInfo = $orderModel->findOne([
            'pay_order_no' => $param['order_no'],
            'user_id' => $userInfo['id']
        ])['data'];

        if (empty($orderInfo)) {
            return dataReturn(-1, '订单不存在');
        }

        if ($orderInfo['pay_status'] == 1) {
            return dataReturn(201, '订单尚未支付，请重试');
        }

        if ($orderInfo['pay_status'] > 2) {
            return dataReturn(-2, '订单异常');
        }
        if ($orderInfo['play_id'] == 4) {
            $orderRecord = new OrderRecord();
            $reward = $orderRecord->field('id,box_id,blindbox_id,award_num,tag_id')->where('order_id', $orderInfo['id'])->select();
            $blindboxBoxesConfig = new BlindboxBoxesConfig();
            $tagMapArr = $blindboxBoxesConfig->where(['play_id' => $orderInfo['play_id'], 'blindbox_id' => $orderInfo['blindbox_id']])->field("picture_image,tag_id")->select()->column('picture_image', 'tag_id');
            $tagNameArr = (new BlindboxTag)->column('name','id');
            $tmpArr = [];
            $goods = new BlindboxGoods();
            foreach ($reward as $v) {
                $tmpArr[] = $v->toArray() +[
                        'tag_name'=>$tagNameArr[$v['tag_id']] ?? '未知',
                        'picture_image'=>$tagMapArr[$v['tag_id']],
                        'items'=>$goods->where(['tag_id'=>$v['tag_id'],'blindbox_id'=>$v['blindbox_id']])->field('goods_image,stock,goods_name,goods_id')->select()
                    ];
            }

            return dataReturn(0, 'success', [
                'detail'=>$tmpArr,'play_id'=>$orderInfo['play_id'],'username'=>$orderInfo['user_name'],'user_id'=>$orderInfo['user_id']
            ]);
        }


        $tagMap = config('shop.special_reward');
        $orderRecord = new OrderRecord();
        $reward = $orderRecord->field('user_id,username,id')->with('detail.tag')
            ->where('order_id', $orderInfo['id'])->find()->toArray();

        $detail = $reward['detail'];
        foreach ($detail as $key => $vo) {

            if ($vo['user_id'] != $userInfo['id']) {
                unset($detail[$key]);
                continue;
            }

            if ($vo['tag_id'] <= 0) {
                $detail[$key]['tag_name'] = $tagMap[$vo['tag_id']];
            } else {
                $detail[$key]['tag_name'] = $vo['tag']['name'];
            }

            unset($detail[$key]['tag']);
        }
        $reward['detail'] = array_values($detail);
        unset($reward['id']);
        return dataReturn(0, 'success', $reward);

    }
    public function getResultOld($param)
    {
        $userInfo = getJWT(getHeaderToken());

        $orderModel = new Order();
        $orderInfo = $orderModel->findOne([
            'pay_order_no' => $param['order_no'],
            'user_id' => $userInfo['id']
        ])['data'];

        if (empty($orderInfo)) {
            return dataReturn(-1, '订单不存在');
        }

        if ($orderInfo['pay_status'] == 1) {
            return dataReturn(201, '订单尚未支付，请重试');
        }

        if ($orderInfo['pay_status'] > 2) {
            return dataReturn(-2, '订单异常');
        }

        $tagMap = config('shop.special_reward');
        $orderRecord = new OrderRecord();
        $reward = $orderRecord->field('user_id,username,id')->with('detail.tag')
            ->where('order_id', $orderInfo['id'])->find()->toArray();

        $detail = $reward['detail'];
        foreach ($detail as $key => $vo) {

            if ($vo['user_id'] != $userInfo['id']) {
                unset($detail[$key]);
                continue;
            }

            if ($vo['tag_id'] <= 0) {
                $detail[$key]['tag_name'] = $tagMap[$vo['tag_id']];
            } else {
                $detail[$key]['tag_name'] = $vo['tag']['name'];
            }

            unset($detail[$key]['tag']);
        }
        $reward['detail'] = array_values($detail);

        return dataReturn(0, 'success', $reward);
    }
    /**
     * 获取重抽结果
     * @param $rewardId
     * @return array
     */
    public function getReward($rewardId)
    {
        $orderRecord = new OrderRecord();
        $reward = $orderRecord->field('user_id,username,id')->with('detail.tag')->where('id', $rewardId)->find()->toArray();
        foreach ($reward['detail'] as $key => $vo) {
            $reward['detail'][$key]['tag_name'] = $vo['tag']['name'];
            unset($reward['detail'][$key]['tag']);
        }

        return dataReturn(0, 'success', $reward);
    }

    /**
     * 超时订单关闭
     * @param $time
     * @return array
     */
    public function overdueClose($time)
    {
        $orderModel = new Order();
        return $orderModel->updateByWhere([
            'close_time' => now(),
            'status' => 7, // 订单关闭
            'pay_status' => 7 // 支付超时
        ], [
            ['type', '=', 2],
            ['pay_status', '=', 1],
            ['create_time', '<', date('Y-m-d H:i:s', time() - $time)]
        ]);
    }

    /**
     * 盲盒订单列表
     * @param $param
     * @return array
     */
    public function getOrderList($param)
    {
        // TODO 暂定1小时
        $this->overdueClose(3600);

        $orderModel = new Order();
        $where[] = ['type', '=', 2];
        if ($param['type'] == 1) {
            $where[] = ['status', '=', 1];
        } else if ($param['type'] == 2) {
            $where[] = ['status', '=', 5];
        }

        $userInfo = getJWT(getHeaderToken());
        $where[] = ['user_id', '=', $userInfo['id']];

        $list = $orderModel->field('order_no,pay_price,status,total_num,blindbox_id')->with(['blindbox.orderDetail'])->where($where)
            ->paginate($param['limit']);
        return pageReturn(dataReturn(0, '', $list));
    }

    /**
     * 盲盒订单列表
     * @param $param
     * @return array
     */
    public function cancelOrder($param)
    {
        $userInfo = getJWT(getHeaderToken());

        $orderModel = new Order();
        $orderInfo = $orderModel->findOne([
            'user_id' => $userInfo['id'],
            'order_no' => $param['order_no'],
            'status' => 1
        ])['data'];

        if (empty($orderInfo)) {
            return dataReturn(-1, '订单信息异常');
        }

        return $orderModel->updateById([
            'status' => 6, // 取消
            'cancel_time' => now()
        ], $orderInfo['id']);
    }

    /**
     * 盲盒订单重新支付
     * @param $param
     * @return array
     */
    public function repay($param)
    {
        if (empty($param['order_no']) || empty($param['platform'])) {
            return dataReturn(-1, '参数错误');
        }

        $param['host'] = getConfByType('api_url')['api_url'];

        // 验证订单信息
        $orderModel = new Order();
        $orderInfo = $orderModel->findOne([
            'order_no' => $param['order_no'],
            'status' => 1
        ])['data'];
        if (empty($orderInfo)) {
            return dataReturn(-1, '订单信息错误');
        }

        $payOrderNo = makeOrderNo('B');
        $orderModel->updateByWhere([
            'pay_order_no' => $payOrderNo
        ], ['id' => $orderInfo['id']]);

        // 拼装参数执行支付
        $orderParam = [
            'id' => $orderInfo['id'],
            'total_num' => $orderInfo['total_num'],
            'unit_price' => $orderInfo['unit_price'],
            'order_no' => $orderInfo['order_no'],
            'trace_id' => 0,
            'blindbox_id' => $orderInfo['blindbox_id'],
            'user_id' => $orderInfo['user_id'],
            'user_name' => $orderInfo['user_name'],
            'pay_price' => $orderInfo['pay_price'],
            'host' => $param['host'],
            'play_id' => $orderInfo['play_id'],
            'pay_order_no' => $payOrderNo,
            'subject' => '盲盒购买' . $orderInfo['total_num'] . '个'
        ];

        // 拼装参数执行支付
        $payProvider = (new PayProvider($orderInfo['pay_way']))->getStrategy();
        return Tools::payByPlatForm($payProvider, $param['platform'] , $orderParam);
    }

    /**
     * 获取奖品发货物流
     * @param $deliverNo
     * @return array
     */
    public function getDeliverExpress($deliverNo)
    {
        $userInfo = getJWT(getHeaderToken());

        $express = [];
        $userBoxDeliverModel = new UserBoxDeliver();
        $info = $userBoxDeliverModel->findOne([
            'deliver_no' => $deliverNo,
            'user_id' => $userInfo['id']
        ], 'id,deliver_no,status,express_status,create_time,express')['data'];

        if (empty($info)) {
            return dataReturn(-1, '该笔单号不存在');
        }

        // 发货详情
        $detail = (new UserBoxDeliverDetail())->with(['reward'])
            ->field('user_box_uuid')->where('deliver_id', $info['id'])->select()->toArray();
        $sendDetail = [];
        foreach ($detail as $vo) {
            $vo = $vo['reward'];
            if (isset($sendDetail[$vo['goods_id']])) {
                $sendDetail[$vo['goods_id']] = [
                    'goods_id' => $vo['goods_id'],
                    'goods_image' => $vo['goods_image'],
                    'goods_name' => $vo['goods_name'],
                    'tag_name' => $vo['tag_name'],
                    'num' => $sendDetail[$vo['goods_id']]['num'] + 1
                ];
            } else {
                $sendDetail[$vo['goods_id']] = [
                    'goods_id' => $vo['goods_id'],
                    'goods_image' => $vo['goods_image'],
                    'goods_name' => $vo['goods_name'],
                    'tag_name' => $vo['tag_name'],
                    'num' => 1
                ];
            }
        }

        // 待发货
        if ($info['status'] == 1) {
            unset($info['express']);
            return dataReturn(0, 'success', compact('info', 'sendDetail', 'express'));
        }

        // 已签收
        if ($info['express_status'] == 3) {
            $express = json_decode($info['express'], true);
            unset($info['express']);
            return dataReturn(0, 'success', compact('info', 'sendDetail', 'express'));
        }

        $userInfo = (new User())->findOne([
            'id' => $userInfo['id']
        ])['data'];
        // 30分钟未更新了
        if (time() - strtotime($info['update_time']) > 1800 || empty($info['express'])) {
            $res = (new ExpressProvider('aliyun'))->getStrategy()->search([
                'no' => $info['express_no'] . ':' . substr($userInfo['phone'], 7, 4),
                'type' => $info['express_code']
            ]);

            if ($res['code'] != 0) {
                return $res;
            }

            $expressInfo = $res['data'];
            $isEnd = false;
            $res = json_decode($expressInfo, true);
            if ($res['status'] == 0 && isset($res['result']['deliverystatus'])) {
                if ($res['result']['deliverystatus'] >= 3) {
                    $isEnd = true;
                }
            }

            // 更新物流
            $userBoxDeliverModel->updateById([
                'express_status' => $isEnd ? 3 : 2,
                'express' => $expressInfo,
                'update_time' => now()
            ], $info['id']);

            $express = $res;

        } else {
            $express = json_decode($info['express'], true);
        }

        unset($info['express']);
        return dataReturn(0, 'success', compact('info', 'sendDetail', 'express'));
    }

    /**
     * 直接完成订单
     * @param $param
     * @return array
     */
    protected function completeOrder($param)
    {
        $luaRedis = new LuaScript();
        $lockKey = 'lottery_bx_' . $param['blindbox_id'] . '_b_' . $param['box_id'];
        $luaRedis->lock($lockKey, 30);

        Db::startTrans();
        try {

            // 调用抽奖算法
            $lotteryProvider = new LotteryProvider($param['play_id']);
            $res = $lotteryProvider->getStrategy()->run($param);
            if ($res['code'] != 0) {
                Db::rollback();
                return $res;
            }

            // 修改订单状态
            (new Order())->where('id', $param['id'])->update([
                'pay_status' => 2,
                'status' => 5,
                'update_time' => date('Y-m-d H:i:s')
            ]);

            // 修改优惠券状态
            if (!empty($param['coupon_code'])) {
                (new UserCoupon())->where('id_code', $param['coupon_code'])->update([
                    'status' => 2,
                    'order_id' => $param['id'],
                    'update_time' => now()
                ]);
            }

            Db::commit();
            $luaRedis->release($lockKey);
        } catch (\Exception $e) {
            Db::rollback();
            $luaRedis->release($lockKey);
            return dataReturn(-12, formatErrMsg($e));
        }

        return dataReturn(0, '支付成功');
    }

    /**
     * 库存检测
     * @param $param
     * @return true
     */
    protected function checkStock($param)
    {
        $hasStock = true;
        // 一番赏 | 全随机
//        if ($param['play_id'] == 1 || $param['play_id'] == 3) {
        if (in_array($param['play_id'],[1,3,4,5])) {
            $redisObj = (new MyRedis())->getObject();
            $stockKey = 'blindbox:' . $param['blindbox_id'] . ':' . $param['box_id'];
            $remainingStock = $redisObj->decrBy($stockKey, $param['num']);

            if ($remainingStock < 0) {
                $hasStock = false;
                $redisObj->incrBy($stockKey, $param['num']); // 回滚库存
            }
        }

        return $hasStock;
    }
}
