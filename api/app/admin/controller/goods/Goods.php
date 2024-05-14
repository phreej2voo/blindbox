<?php

namespace app\admin\controller\goods;

use app\admin\controller\Base;
use app\admin\service\goods\GoodsService;
use app\model\marketing\Coupon;
use app\model\marketing\CouponYz;

class Goods extends Base
{
    /**
     * 商品列表
     */
    public function index()
    {
        $goodsService = new GoodsService();
        return json($goodsService->getGoodsList(input('param.')));
    }


    public function coupon()
    {
        $couponModel = new Coupon();
        $data = $couponModel->field("id,name")->where(['open_flag'=>1,'is_limit_num'=>2])->select();
        return jsonReturn(0,'success',$data);
    }
    /**
     * 添加商品
     */
    public function add()
    {
        $goodsService = new GoodsService();
        return json($goodsService->addGoods(input('post.')));
    }

    /**
     * 编辑商品
     */
    public function edit()
    {
        $goodsService = new GoodsService();
        return json($goodsService->editGoods(input('post.')));
    }

    /**
     * 删除商品
     */
    public function del()
    {
        $goodsService = new GoodsService();
        return json($goodsService->delGoods(input('param.id')));
    }

    /**
     * 商品上下架
     */
    public function shelves()
    {
        $goodsService = new GoodsService();
        return json($goodsService->shelvesGoods(input('post.')));
    }

    /**
     * 有赞商品上传文件添加库存
     * @return \think\response\Json
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function couponinventory()
    {
        $param = input('post.');
        $goods = new \app\model\goods\Goods();
        $result = $goods->where('id',$param['id'])->find();
        if (empty($result)) {
            return jsonReturn(-1,'商品不存在');
        }
        if ($result['delete_flag'] == 2) {
            return jsonReturn(-1,'商品不存在');
        }

        if ($result['goods_type'] == 4) {
            if (empty($_FILES['csv']['tmp_name'])) {
                return jsonReturn(-3, '有赞优惠券配置错误');
            }
            $couponYz = readcsv($_FILES['csv']['tmp_name']);
            $couponYz = array_unique($couponYz);
            if (empty($couponYz)) {
                return jsonReturn(-3, '有赞优惠券配置错误');
            }
            $couponYzArr = [];
            $batchNo = date('YmdHis');
            $date = now();
            foreach ($couponYz as $v) {
                $couponYzArr[] = [
                    'batch_no'=>$batchNo,
                    'cdk'=>$v,
                    'status'=>1,
                    'create_time'=>$date,
                ];
            }

            // 增加库存
            $goods->incData(['id'=>$param['id']],'stock',count($couponYzArr));
            $couponYzArr && (new CouponYz())->insertAll($couponYzArr);
        }

        return jsonReturn(0);
    }

    /**
     * 上传配置文件
     */
    public function uploadCsvFile()
    {
        /** @var  $file */
        $file = request()->file('file');
        return json((new GoodsService())->uploadCsvFile($file));
    }
}
