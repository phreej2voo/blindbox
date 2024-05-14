<?php
namespace app\api\service\marketing;

use app\model\order\Order;

class RankService
{
    /**
     * 获取排行榜数据
     * @param $param
     * @return array
     */
    public function getRank($param)
    {
        $userInfo = getUserInfo();
        $orderModel = new Order();

        // 今日
        if ($param['type'] == 1) {
            $time = date('Y-m-d') . ' 00:00:00';
        } else { // 本周
            $time = date('Y-m-d', strtotime('this week')) . ' 00:00:00';
        }

        $rankList = $orderModel->with(['avatar'])->field('sum(pay_price) as payPrice,user_id,user_name')
            ->group('user_id')->where('create_time', '>=', $time)->order('payPrice desc')->select()->toArray();
        if (empty($rankList)) {
            return dataReturn(0, 'success', [
                'rankList' => [],
                'myRank' => [
                    'payPrice' => 0,
                    'avatar' => $userInfo['avatar']
                ],
                'myRnkInfo' => [
                    'num' => 0,
                    'differ' => 0
                ]
            ]);
        }

        // 本人信息
        $myRank = $orderModel->with(['avatar'])->field('sum(pay_price) as payPrice,user_id,user_name')->where('user_id', $userInfo['id'])
            ->where('create_time', '>=', $time)->find();

        if (empty($myRank['payPrice'])) {
            $myRank['payPrice'] = 0;
            $myRank['avatar'] = $userInfo['avatar'];
            $myRank['user_id'] = $userInfo['id'];
            $myRank['user_name'] = $userInfo['nickname'];
        } else {
            $myRank['payPrice'] = ceil($myRank['payPrice'] * 100);
            if (!empty($myRank['avatar'])) {
                $myRank['avatar'] = $myRank['avatar']['avatar'];
            }
        }

        $num = '未上榜';
        $differ = 0;

        foreach ($rankList as $key => $vo) {
            $rankList[$key]['payPrice'] = ceil($vo['payPrice'] * 100);
            if (!empty($vo['avatar'])) {
                $rankList[$key]['avatar'] = $vo['avatar']['avatar'];
            }

            if ($vo['user_id'] == $myRank['user_id']) {
                $num = $key + 1;
            }
        }

        if ($num == '未上榜') {
            $differ = $rankList[count($rankList) - 1]['payPrice'] - $myRank['payPrice'];
        }

        $myRnkInfo = [
            'num' => $num,
            'differ' => $differ
        ];

        return dataReturn(0, 'success', compact('rankList', 'myRank', 'myRnkInfo'));
    }
}