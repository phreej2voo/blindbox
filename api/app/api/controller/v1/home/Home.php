<?php

namespace app\api\controller\v1\home;

use app\api\service\home\HomeService;
use app\BaseController;
use app\model\box\Blindbox;
use utils\MyRedis;
use utils\Tools;

class Home extends BaseController
{
    // 首页
    public function index()
    {
        $homeService = new HomeService();
        return json($homeService->getHomeData(request()->get()));
    }

    // 首页
    public function indexNew()
    {
        $homeService = new HomeService();
        return json($homeService->getHomeDataNew(request()->get()));
    }


    /**
     * 首页广告
     */
    public function ad()
    {
        $homeService = new HomeService();
        return json($homeService->getAdList());
    }

    /**
     * 中奖记录
     */
    public function award()
    {
        $homeService = new HomeService();
        return json($homeService->getNewAward());
    }

    /**
     * 首页盲盒搜索
     * @return \think\response\Json
     */
    public function search()
    {
        $result = (new Blindbox())->field('id,name,desc,pic,sales,price')
            ->where('play_id', input('param.play_id','1'))->where('status', 1)
            ->whereRaw('name like "%'.input('param.name').'%"')
            ->where('is_delete', 1)
            ->order('sort desc')->limit($param['limit'] ?? 10)->select()->toArray();
        return json(dataReturn(0, 'success', $result ?? []));
    }

    /**
     * 根据盲盒分类查询
     * @return \think\response\Json
     * @throws \think\db\exception\DbException
     */
    public function type()
    {
        $where[] = ['index_type','=',input('param.index_type','4')];
        $where[] = ['status','=',1];
        $where[] = ['is_delete','=',1];
        if (input('param.play_id')) {
            $where[] = ['play_id','=',input('param.play_id')];
        }
        if (input('param.type')) {
            $where[] = ['type','=',input('param.type')];
        }
        $list = (new Blindbox())->field('id,name,desc,pic,sales,price')->where($where)->order('sort desc')->paginate(input('param.limit',15));
        return json(pageReturn(dataReturn(0, 'success', $list)));
    }
}
