<?php

namespace app\command\task;

use app\model\marketing\Roll;
use app\model\marketing\RollDetail;
use app\model\marketing\RollReward;
use app\model\marketing\RollUser;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use think\console\Command;
use think\console\Input;
use think\console\Output;
use think\facade\Db;
use think\facade\Log;
use utils\MyRedis;
use utils\Tools;

/**
 * roll房开奖定时器
 */
class RollCheck extends Command
{
    protected function configure()
    {
        $this->setName('roll房开奖定时器');
    }

    protected function execute(Input $input, Output $output)
    {
        try {

            $rollModel = new Roll();
            $rollDetailModel = new RollDetail();
            $userBoxHotModel = new UserBoxHot();
            $userBoxLogModel = new UserBoxLog();
            $rollUserModel = new RollUser();
            $rollRewardModel = new RollReward();

            $list = $rollModel->where('reward_time', '<', now())->where('status', 1)->select()->toArray();
            if (empty($list)) {
                $output->writeln('没有任何到期的roll房');
                return false;
            }

            $rollIds = array_column($list, 'id');
            $rollModel->whereIn('id', $rollIds)->update(['status' => 5]); // 防止重复扫描

            foreach ($list as $vo) {
                $param = $vo;
                // 参与人数是否达到9人
                $total = $rollUserModel->where('roll_id', $param['id'])->count('id');

                // 走退款逻辑
                if ($total < 9) {

                    if ($param['is_official'] == 2) {
                        // 把设置的奖品退还给创建人
                        $rollDetailList = $rollDetailModel->field('box_data')->where('roll_id', $param['id'])->select()->toArray();

                        $uuids = [];
                        $dataArr = [];
                        foreach ($rollDetailList as $vo) {
                            $data = json_decode($vo['box_data'], true);
                            $uuids[] = $data['uuid'];
                            $dataArr[] = $data;
                        }

                        $userBoxHotModel->insertAll($dataArr);
                        $userBoxLogModel->whereIn('uuid', $uuids)->update([
                            'status' => 1
                        ]);
                    }

                    $rollModel->where('id', $param['id'])->update(['status' => 3]);
                    continue;
                }

                // 走发放逻辑
                // 参与人
                $userList = $rollUserModel->field('user_id,username')->where('roll_id', $param['id'])->select()->toArray();
                $userIds = array_column($userList, 'user_id');
                $userId2Name = array_column($userList, 'username', 'user_id');

                $rollDetailList = $rollDetailModel->field('box_data')->where('roll_id', $param['id'])->select()->toArray();

                $dataArr = [];
                $reward = [];
                foreach ($rollDetailList as $key => $vo) {
                    // 发完结束
                    if (empty($userIds)) {
                        break;
                    }

                    $data = json_decode($vo['box_data'], true);
                    unset($data['id']);

                    // 随机一个中奖人
                    $key2 = array_rand($userIds);
                    $rewardUser = $userIds[$key2];
                    unset($userIds[$key2]);

                    $data['user_id'] = $rewardUser;
                    $data['uuid'] = uniqid();
                    $data['create_time'] = now();
                    $dataArr[] = $data;
                    $reward[] = [
                        'roll_id' => $param['id'],
                        'roll_name' => $param['title'],
                        'goods_id' => $data['goods_id'],
                        'goods_name' => $data['goods_name'],
                        'goods_image' => $data['goods_image'],
                        'user_id' => $rewardUser,
                        'username' => $userId2Name[$rewardUser],
                        'create_time' => now()
                    ];

                    unset($rollDetailList[$key]);
                }

                // 写入赏袋
                $userBoxHotModel->insertAll($dataArr);
                $userBoxLogModel->insertAll($dataArr);
                // 记录中奖记录
                $rollRewardModel->insertAll($reward);

                // 如果未发完,归还给开房人
                if ($param['is_official'] == 2 && !empty($rollDetailList)) {
                    $uuids = [];
                    $dataArr = [];
                    foreach ($rollDetailList as $vo) {
                        $data = json_decode($vo['box_data'], true);
                        $uuids[] = $data['uuid'];
                        $dataArr[] = $data;
                    }

                    $userBoxHotModel->insertAll($dataArr);
                    $userBoxLogModel->whereIn('uuid', $uuids)->update([
                        'status' => 1
                    ]);
                }

                $rollModel->where('id', $param['id'])->update(['status' => 2]);
            }

        } catch (\Exception $e) {
            Log::error('roll房发放奖励异常: ' . formatErrMsg($e));
        }


        $output->writeln('roll房开奖执行结束');
    }
}
