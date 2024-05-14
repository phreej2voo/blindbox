<?php

namespace app\api\controller\v1\marketing;

use app\api\controller\Base;
use app\api\service\marketing\ConflateService;

class Conflate extends Base
{
    /**
     * 获取合成活动列表
     */
    public function getList(ConflateService $conflateService)
    {
        return json($conflateService->getList(input('param.')));
    }

    /**
     * 获取合成详情
     */
    public function detail(ConflateService $conflateService)
    {
        return json($conflateService->getDetail(input('param.id')));
    }

    /**
     * 获取我的合成材料
     */
    public function getMyGoods(ConflateService $conflateService)
    {
        return json($conflateService->getMyGoods(input('param.id')));
    }

    /**
     * 材料投放
     */
    public function putIn(ConflateService $conflateService)
    {
        return json($conflateService->putIn(input('post.')));
    }

    /**
     * 获取进度详情
     */
    public function progressDetail(ConflateService $conflateService)
    {
        return json($conflateService->getProgressDetail(input('param.id')));
    }

    /**
     * 移除碎片
     */
    public function remove(ConflateService $conflateService)
    {
        return json($conflateService->remove(input('post.')));
    }

    /**
     * 合成
     */
    public function do(ConflateService $conflateService)
    {
        return json($conflateService->do(input('post.progress_id')));
    }

    /**
     * 合成记录
     */
    public function log(ConflateService $conflateService)
    {
        return json($conflateService->conflateLog(input('param.')));
    }
}