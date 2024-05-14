<?php
namespace app\api\controller\v1\marketing;

use app\api\controller\Base;
use app\api\service\marketing\RollService;

class Roll extends Base
{
    /**
     * 获取roll房列表
     */
    public function list(RollService $rollService)
    {
        return json($rollService->getRollList(input('param.')));
    }

    /**
     * roll房详情
     */
    public function detail(RollService $rollService)
    {
        return json($rollService->getRollDetail(input('param.id')));
    }

    /**
     * 加入roll房
     */
    public function join(RollService $rollService)
    {
        return json($rollService->joinRoll(input('post.')));
    }

    /**
     * 创建roll房
     */
    public function create(RollService $rollService)
    {
        return json($rollService->createRoll(input('post.')));
    }

    /**
     * 获取我的奖品
     */
    public function getMyGoods(RollService $rollService)
    {
        return json($rollService->getMyGoods());
    }

    /**
     * 中奖记录
     */
    public function reward(RollService $rollService)
    {
        return json($rollService->getReward(input('param.')));
    }

    /**
     * 参与记录
     */
    public function joinLog(RollService $rollService)
    {
        return json($rollService->getJoinLog(input('param.')));
    }
}