<?php

namespace app\api\service\home;

use app\api\service\activity\SliderService;
use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxType;
use app\model\marketing\HomeAd;
use app\model\order\OrderRecordDetail;
use utils\Tools;

class HomeService
{
    /**
     * 首页聚合数据
     * @return array
     */
    public function getHomeDataNew($param)
    {
        if (!checkOpen()) {
            return dataReturn(404, '站点正在维护');
        }

        // 幻灯数据
        $activityService = new SliderService();
        $sliderList = $activityService->getSliderData();

       # 查询盲盒分类
        $blindboxTypeModel = new BlindboxType();
        $typeArr = $blindboxTypeModel->where(['is_delete'=>1,'status'=>1])->order('sort','desc')->field('name,id')->select();
        $sysBaseArr = getConfByType('sys_base');
        $indexArr = json_decode($sysBaseArr['index_config'],1);
        $resultArr = [];
        foreach ($indexArr as $k=>$v) {
                $resultArr[]=[
                    'id'=>$k,
                    'title'=>$v,
                    'items'=>(new Blindbox())->field('id,name,desc,pic,sales,price')->where('index_type',$k)
                        ->where('play_id', $param['play_id'])->where('status', 1)
                        ->where('is_delete', 1)
                        ->order('sort desc')->limit($param['limit'] ?? 10)->select()->toArray()

                ];
        }


        return dataReturn(0, 'success', [
            'index_config'=>[
                'kefu_wechat'=>$sysBaseArr['kefu_wechat'] ?? '',
                'zhibo_config'=>$sysBaseArr['zhibo_config'] ?? '',
            ],
            'banner' => $sliderList['data'],// 轮播图
            'box_type'=>$typeArr,// 盲盒分类
            'index_type_list'=>$resultArr // 渲染首页的其他数据
        ]);
    }

    public function getHomeData($param)
    {
        if (!checkOpen()) {
            return dataReturn(404, '站点正在维护');
        }

        // 幻灯数据
        $activityService = new SliderService();
        $sliderList = $activityService->getSliderData();
        $goodsList = $this->getGoodsData($param);
        return dataReturn(0, 'success', [
            'slider' => $sliderList['data'],
            'goods' => $goodsList['data'],


        ]);
    }
    /**
     * 首页盲盒列表数据
     * @param $param
     * @return array
     */
    public function getGoodsData($param)
    {
        $blindboxModel = new Blindbox();
        $blindboxList = $blindboxModel->field('id,name,desc,pic,sales,price')
            ->where('play_id', $param['play_id'])->where('status', 1)
            ->where('is_delete', 1)
            ->order('sort desc')->paginate($param['limit']);

        // 补充箱子数量和箱子号
        $blindboxBoxes = new BlindboxBoxes();
        $blindboxList->each(function ($item) use ($blindboxBoxes) {

            $item->box_num = $blindboxBoxes->where('blindbox_id', $item->id)->count('id');

            $oneBoxInfo = $blindboxBoxes->where('blindbox_id', $item->id)->findOrEmpty();
            $item->one_box_num = $oneBoxInfo['stock'] + $oneBoxInfo['sales'];
            $item->now_box_no = $oneBoxInfo['box_no'];
        });

        return dataReturn(0, 'success', $blindboxList);
    }

    /**
     * 获取广告列表
     * @return array
     */
    public function getAdList()
    {
        return (new HomeAd())->getAllList(['status' => 1], 'pic,type');
    }

    /**
     * 获取中奖列表
     * @return array
     */
    public function getNewAward()
    {
        $time = date('Y-m-d 00:00:00', strtotime('-3 days'));

        $orderRecordDetailModel = new OrderRecordDetail();
        $list = $orderRecordDetailModel->getLimitList([
            ['create_time', '>=', $time]
        ], 20, 'user_name,goods_name')['data'];

        foreach ($list as $key => $vo) {
            $list[$key]['user_name'] = Tools::desensitizeString($vo['user_name']);
        }

        return dataReturn(0, 'success', $list);
    }
}
