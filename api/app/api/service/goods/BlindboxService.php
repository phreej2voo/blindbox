<?php

namespace app\api\service\goods;

use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxGoods;
use app\model\box\BlindboxTag;
use app\model\goods\Goods;
use app\model\order\OrderRecordDetail;
use utils\Tools;

class BlindboxService
{
    /**
     * 获取盲盒商品详情
     * @param $param
     * @return array
     */
    public function getBlindboxDetail($param)
    {
        $blindboxBoxesModel = new BlindboxBoxes();
        // 未传递箱号进入,确定箱号
        if (empty($param['box_id'])) {
            $blindboxBoxesInfo = $blindboxBoxesModel->where('status', 1)
                ->where('blindbox_id', $param['id'])->order('box_no asc')->findOrEmpty();

            if (empty($blindboxBoxesInfo)) {
                $param['box_id'] = $blindboxBoxesModel->where('blindbox_id', $param['id'])->order('box_no asc')->findOrEmpty()->box_no;
            } else {
                $param['box_id'] = $blindboxBoxesInfo['box_no'];
            }
        }

        $blindboxList = (new BlindboxBoxesDetail())->field('blindbox_id,box_id,sales,stock,blindbox_goods_id,tag_id')
            ->with(['tag'])->where('blindbox_id', $param['id'])
            ->where('box_id', $param['box_id'])
            ->select()->toArray();

        $goodsList = (new BlindboxGoods())->field(['goods_id', 'goods_name', 'goods_image', 'price', 'probability'])
            ->where('blindbox_id', $param['id'])->select()->toArray();

        $goodsId2Info = [];
        foreach ($goodsList as $vo) {
            $goodsId2Info[$vo['goods_id']] = $vo;
        }

        foreach ($blindboxList as $key => $vo) {
            $blindboxList[$key]['goods_id'] = $goodsId2Info[$vo['blindbox_goods_id']]['goods_id'];
            $blindboxList[$key]['goods_name'] = $goodsId2Info[$vo['blindbox_goods_id']]['goods_name'];
            $blindboxList[$key]['goods_image'] = $goodsId2Info[$vo['blindbox_goods_id']]['goods_image'];
            $blindboxList[$key]['price'] = $goodsId2Info[$vo['blindbox_goods_id']]['price'];
            $blindboxList[$key]['probability'] = $goodsId2Info[$vo['blindbox_goods_id']]['probability'];
            $blindboxList[$key]['total_stock'] = $vo['sales'] + $vo['stock'];
        }

        if (empty($blindboxList)) {
            return dataReturn(-10, '没有了');
        }

        $specialReward = config('shop.special_reward');
        $totalNum = 0;
        $salesNum = 0;
        foreach ($blindboxList as $key => $vo) {
            if (!empty($vo['tag_name'])) {
                $totalNum += ($vo['sales'] + $vo['stock']);
                $salesNum += $vo['sales'];
            }

           if (empty($vo['tag_name'])) {
               $blindboxList[$key]['tag_name'] = $specialReward[$vo['tag_id']];
           }
        }
        // 盲盒信息
        $blindboxInfo = (new Blindbox())->findOne(['id' => $param['id']], 'price,name,pic,play_id')['data'];
        // 最大箱号
        $maxBoxNo = $blindboxBoxesModel->where('blindbox_id', $param['id'])->max('box_no');

        // 如果是哈希赏，需要聚合
        if ($blindboxInfo['play_id'] == 2) {

            $hashList = [];
            foreach ($blindboxList as $vo) {
                if (isset($hashList[$vo['tag_name']])) {
                    $hashList[$vo['tag_name']]['probability'] = $hashList[$vo['tag_name']]['probability'] + $vo['probability'];
                } else {
                    $hashList[$vo['tag_name']] = [
                        'tab_name' => $vo['tag_name'],
                        'probability' => $vo['probability']
                    ];
                }

                $hashList[$vo['tag_name']]['list'][] = $vo;
            }

            $blindboxList = array_values($hashList);
        }

        return dataReturn(0, 'success', [
            'now_box' => $param['box_id'],
            'total_num' => $totalNum,
            'left_num' => $totalNum - $salesNum,
            'max_box_no' => $maxBoxNo,
            'info' => $blindboxInfo,
            'list' => $blindboxList
        ]);
    }

    /**
     * 获取盲盒箱子商品详情
     * @param $param
     * @return array
     */
    public function getBlindboxBoxesDetail($param)
    {
        $blindboxBoxesDetailModel = new BlindboxBoxesDetail();
        $blindboxList = $blindboxBoxesDetailModel->with('goods')->where('blindbox_id', $param['id'])
            ->where('box_id', $param['box_id'])->select()->toArray();

        return dataReturn(0, 'success', $blindboxList);
    }

    /**
     * 获取盲盒基础信息
     * @param $param
     * @return array
     */
    public function getBlindboxInfo($param)
    {
        $blindboxModel = new Blindbox();
        return $blindboxModel->findOne(['id' => $param['blindbox_id']]);
    }

    /**
     * 获取商品介绍
     * @param $param
     * @return array
     */
    public function getGoodsInfo($param)
    {
        $goodsModel = new Goods();
        $goodsInfo = $goodsModel->field('id,name,sub_title,image,price,content')
            ->where([
                'id' => $param['id'],
                'status' => 1,
                'goods_type' => 2,
                'delete_flag' => 1
            ])->find();

        if (empty($goodsInfo)) {
            return dataReturn(-1, '商品信息错误');
        }

        return dataReturn(0, 'success', $goodsInfo);
    }

    /**
     * 换箱信息
     * @param $param
     * @return array
     */
    public function getBoxes($param)
    {
        try {

            $blindboxBoxesModel = new BlindboxBoxes();
            $list = $blindboxBoxesModel->field('id,box_no,blindbox_id,sales,stock,status')
                ->where('blindbox_id', $param['id'])->select();

            $boxDetail = (new BlindboxBoxesDetail())->field('tag_id,box_id,sales,stock')->where('blindbox_id', $param['id'])
                ->select()->toArray();
            $boxDetail2Map = [];
            foreach ($boxDetail as $vo) {

                $vo['tag_name'] = Tools::getTagName($vo['tag_id'])['data'];
                $boxDetail2Map[$vo['box_id']][] = $vo;
            }

            foreach ($list as $key => $vo) {
                $list[$key]['detail'] = $boxDetail2Map[$vo['box_no']];
            }
        } catch (\Exception $e) {
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, 'success', $list);
    }

    /**
     * 中奖记录
     * @param $param
     * @return array
     */
    public function getRewardList($param)
    {
        $list = (new OrderRecordDetail())->getPageList($param['limit'], [
            'blindbox_id' => $param['blindbox_id'],
            'box_id' => $param['box_id']
        ], 'user_name,user_avatar,create_time,tag_id');

        $blindboxTag = (new BlindboxTag())->field('id,name')->where('status', 1)->select();
        $tag2Name = [];
        foreach ($blindboxTag as $vo) {
            $tag2Name[$vo['id']] = $vo['name'];
        }
        $specialReward = config('shop.special_reward');

        $list['data']->each(function ($item) use ($specialReward, $tag2Name) {
            if ($item->tag_id <= 0) {
                $item->tag_name = $specialReward[$item->tag_id];
            } else {
                $item->tag_name = $tag2Name[$item->tag_id];
            }

            return $item;
        });

        return $list;
    }
}