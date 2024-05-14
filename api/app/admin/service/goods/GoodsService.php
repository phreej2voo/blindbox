<?php

namespace app\admin\service\goods;

use app\admin\service\log\AdminLogService;
use app\admin\validate\GoodsValidate;
use app\model\goods\Goods;
use app\model\goods\GoodsRule;
use app\model\goods\GoodsRuleExtend;
use app\model\marketing\Coupon;
use app\model\marketing\CouponYz;
use app\model\system\SysAttachment;
use Chance\Log\facades\OperationLog;
use think\exception\ValidateException;
use think\facade\App;
use think\facade\Db;
use think\facade\Filesystem;

class GoodsService
{
    /**
     * 获取商品列表
     * @param $param
     * @return array
     */
    public function getGoodsList($param)
    {
        $goodsModel = new Goods();

        $where[] = ['delete_flag', '=', 1];
        if (!empty($param['cate_id'])) {
            $where[] = ['cate_id', '=', $param['cate_id']];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        if (!empty($param['name'])) {
            $where[] = ['name', 'like', '%' . $param['name'] . '%'];
        }

        if (isset($param['goods_type']) && !empty($param['goods_type'])) {
            $where[] = ['goods_type', '=', $param['goods_type']];
        }

        $list = $goodsModel->with(['cate', 'rule', 'ruleExtend'])->where($where)->order('id desc')->paginate($param['limit'])
            ->each(function ($item) {
            if ($item->type == 2 && !empty($item->ruleExtend->toArray())) {
                $stock = 0;
                foreach ($item->ruleExtend as $v) {
                    $stock += $v['stock'];
                }
                $item->stock = $stock;
            }
        });

        return pageReturn(dataReturn(0, 'success', $list));
    }
    public function addGoodsOld($param)
    {
        try {
            validate(GoodsValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        if ($param['type'] == 2 && (empty($param['preItem']) || empty($param['final']))) {
            return dataReturn(-2, '请生成规格属性');
        }

        $goodsModel = new Goods();

        // 检测标题重复
        $where[] = ['name', '=', $param['name']];
        $has = $goodsModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-3, '该商品标题已经存在');
        }

        Db::startTrans();
        try {

            $goodsId = $goodsModel->insertGetId([
                'cate_id' => $param['cate_id'],
                'goods_type' => $param['goods_type'],
                'type' => $param['type'],
                'name' => $param['name'],
                'sub_title' => $param['sub_title'],
                'image' => $param['image'],
                'content' => $param['content'] ?? '',
                'stock' => $param['stock'],
                'price' => $param['price'],
                'recovery_price' => $param['recovery_price'],
                'cost_price' => $param['cost_price'],
                'integral_price' => $param['integral_price'],
                'status' => $param['status'],
                'sales' => 0,
                'delivery_fee' => $param['delivery_fee'],
                'sort' => $param['sort'],
                'conflate_val' => $param['conflate_val'],
                'create_time' => now()
            ]);
            (new AdminLogService())->write([], OperationLog::getLog());

            if (!empty($param['preItem'][0]['item'][0])) {
                $goodsRuleModel = new GoodsRule();
                $goodsRuleModel->insert([
                    'goods_id' => $goodsId,
                    'rule' => json_encode($param['preItem'])
                ]);
                (new AdminLogService())->write([], OperationLog::getLog());
            }

            if (!empty($param['final'])) {
                // 规格详情
                $goodsRuleExtendModel = new GoodsRuleExtend();
                $batchParam = [];
                foreach ($param['final'] as $vo) {
                    $batchParam[] = [
                        'goods_id' => $goodsId,
                        'sku' => implode('※', $vo['sku']),
                        'stock' => $vo['stock'],
                        'sales' => 0,
                        'image' => $vo['image'],
                        'unique' => uniqid(),
                        'price' => $vo['price'],
                        'recovery_price' => $vo['recovery_price'],
                        'cost_price' => $vo['cost_price'],
                        'integral_price' => $vo['integral_price'],
                        'create_time' => now()
                    ];
                }
                $goodsRuleExtendModel->insertAll($batchParam);

                // 选取第一个产品的售价当多规格售价
                $goodsModel->updateById([
                    'recovery_price' => $batchParam[0]['recovery_price'],
                    'price' => $batchParam[0]['price'],
                    'integral_price' => $batchParam[0]['integral_price']
                ], $goodsId);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-4, $e->getMessage());
        }

        return dataReturn(0, '添加成功');
    }
    /**
     * 新增商品
     * @param $param
     * @return array
     */
    public function addGoods($param)
    {
        try {
            validate(GoodsValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }
        if ($param['type'] == 2 && (empty($param['preItem']) || empty($param['final']))) {
            return dataReturn(-2, '请生成规格属性');
        }
        if (!in_array($param['goods_type'],range(1,4))) {
            return dataReturn(-2, '商品属性错误');
        }
        $goodsModel = new Goods();

        // 检测标题重复
        $where[] = ['name', '=', $param['name']];
        $has = $goodsModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-3, '该商品标题已经存在');
        }
        // 优惠券商品 必须传递优惠券id
        if ($param['goods_type'] == 3) {
            if (empty($param['coupon_id'])) {
                return dataReturn(-3, '优惠券配置错误');
            }
            $coupon = (new Coupon)->where('id',$param['coupon_id'])->where('open_flag',1)->find();
            if (empty($coupon)) {
                return dataReturn(-3, '优惠券配置错误');
            }
            if ($coupon['is_limit_num'] != 2) {
                return dataReturn(-3, '请配置无限库存优惠券');
            }
            if ($param['type'] == 2) {
                return dataReturn(-2, '优惠券不能使用规格属性');
            }
        }

        // 有赞优惠券 必须上传csv
        if ($param['goods_type'] == 4) {
            // if (empty($_FILES['csv']['tmp_name'])) {
            if (empty($param['csv'])) {
                return dataReturn(-3, '有赞优惠券配置错误');
            }
           // $couponYz = readcsv($_FILES['csv']['tmp_name']);
           $couponYz = readcsv($param['csv']);
           $couponYz = array_unique($couponYz);
            if (empty($couponYz)) {
                return dataReturn(-3, '有赞优惠券配置错误');
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
            if ($param['type'] == 2) {
                return dataReturn(-2, '优惠券不能使用规格属性');
            }
            $param['stock']  = count($couponYzArr);
        }

        Db::startTrans();
        try {

            $goodsId = $goodsModel->insertGetId([
                'coupon_id'=>$param['coupon_id'] ?? 0,
                'cate_id' => $param['cate_id'],
                'goods_type' => $param['goods_type'],
                'type' => $param['type'],
                'name' => $param['name'],
                'sub_title' => $param['sub_title'],
                'image' => $param['image'],
                'content' => $param['content'] ?? '',
                'stock' => $param['stock'],
                'price' => $param['price'],
                'recovery_price' => $param['recovery_price'],
                'cost_price' => $param['cost_price'],
                'integral_price' => $param['integral_price'],
                'status' => $param['status'],
                'sales' => $param['sales'] ?? 0,
                'delivery_fee' => $param['delivery_fee'],
                'sort' => $param['sort'],
                'conflate_val' => $param['conflate_val'],
                'create_time' => now()
            ]);
            (new AdminLogService())->write([], OperationLog::getLog());

            if (!empty($param['preItem'][0]['item'][0])) {
                $goodsRuleModel = new GoodsRule();
                $goodsRuleModel->insert([
                    'goods_id' => $goodsId,
                    'rule' => json_encode($param['preItem'])
                ]);
                (new AdminLogService())->write([], OperationLog::getLog());
            }

            if (!empty($param['final'])) {
                // 规格详情
                $goodsRuleExtendModel = new GoodsRuleExtend();
                $batchParam = [];
                foreach ($param['final'] as $vo) {
                    $batchParam[] = [
                        'goods_id' => $goodsId,
                        'sku' => implode('※', $vo['sku']),
                        'stock' => $vo['stock'],
                        'sales' => 0,
                        'image' => $vo['image'],
                        'unique' => uniqid(),
                        'price' => $vo['price'],
                        'recovery_price' => $vo['recovery_price'],
                        'cost_price' => $vo['cost_price'],
                        'integral_price' => $vo['integral_price'],
                        'create_time' => now()
                    ];
                }
                $goodsRuleExtendModel->insertAll($batchParam);

                // 选取第一个产品的售价当多规格售价
                $goodsModel->updateById([
                    'recovery_price' => $batchParam[0]['recovery_price'],
                    'price' => $batchParam[0]['price'],
                    'integral_price' => $batchParam[0]['integral_price']
                ], $goodsId);
            }
//            有赞优惠券插入
            !empty($couponYzArr) && (new CouponYz())->insertAll($couponYzArr);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-4, $e->getMessage());
        }

        return dataReturn(0, '添加成功');
    }

    /**
     * 编辑商品
     * @param $param
     * @return array
     */
    public function editGoods($param)
    {
        try {
            validate(GoodsValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        if ($param['type'] == 2 && (empty($param['preItem']) || empty($param['final']))) {
            return dataReturn(-2, '请生成规格属性');
        }

        $goodsModel = new Goods();
        // 检测标题重复
        $where[] = ['name', '=', $param['name']];
        $where[] = ['id', '<>', $param['id']];
        $has = $goodsModel->checkUnique($where)['data'];
        if (!empty($has)) {
            return dataReturn(-3, '该商品标题已经存在');
        }

        Db::startTrans();
        try {

            $goodsModel->where('id', $param['id'])->update([
                'cate_id' => $param['cate_id'],
                'goods_type' => $param['goods_type'],
                'type' => $param['type'],
                'name' => $param['name'],
                'sub_title' => $param['sub_title'],
                'image' => $param['image'],
                'content' => $param['content'] ?? '',
                'stock' => $param['stock'],
                'price' => $param['price'],
                'recovery_price' => $param['recovery_price'],
                'cost_price' => $param['cost_price'],
                'integral_price' => $param['integral_price'],
                'status' => $param['status'],
                'sales' => $param['sales'] ?? 0,
                'delivery_fee' => $param['delivery_fee'],
                'sort' => $param['sort'],
                'conflate_val' => $param['conflate_val'],
                'create_time' => now()
            ]);
            (new AdminLogService())->write([], OperationLog::getLog());

            if (!empty($param['preItem'][0]['item'][0])) {
                $goodsRuleModel = new GoodsRule();
                $goodsRuleModel->where('goods_id', $param['id'])->delete();
                $goodsRuleModel->insert([
                    'goods_id' => $param['id'],
                    'rule' => json_encode($param['preItem'])
                ]);
                (new AdminLogService())->write([], OperationLog::getLog());
            }

            // 规格详情
            if (!empty($param['final'])) {
                $goodsRuleExtendModel = new GoodsRuleExtend();
                $goodsRuleExtendModel->where('goods_id', $param['id'])->delete();
                $batchParam = [];
                foreach ($param['final'] as $vo) {
                    $batchParam[] = [
                        'goods_id' => $param['id'],
                        'sku' => implode('※', $vo['sku']),
                        'stock' => $vo['stock'],
                        'sales' => 0,
                        'image' => $vo['image'],
                        'unique' => uniqid(),
                        'price' => $vo['price'],
                        'recovery_price' => $vo['recovery_price'],
                        'cost_price' => $vo['cost_price'],
                        'integral_price' => $vo['integral_price'],
                        'create_time' => now()
                    ];
                }
                $goodsRuleExtendModel->insertAll($batchParam);

                // 选取第一个产品的售价当多规格售价
                $goodsModel->updateById([
                    'recovery_price' => $batchParam[0]['recovery_price'],
                    'price' => $batchParam[0]['price'],
                    'integral_price' => $batchParam[0]['integral_price']
                ], $param['id']);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-4, $e->getTraceAsString());
        }

        return dataReturn(0, '更新成功');
    }

    /**
     * 删除商品
     * @param $id
     * @return array
     */
    public function delGoods($id)
    {
        $goodsModel = new Goods();
        $res = $goodsModel->updateById(['delete_flag' => 2], $id);

        if ($res['code'] == 0) {
            $res['msg'] = '删除成功';
        }

        return $res;
    }

    /**
     * 上下架操作
     * @param $param
     * @return array
     */
    public function shelvesGoods($param)
    {
        $goodsModel = new Goods();
        $res = $goodsModel->updateByIds([
            'status' => $param['type']
        ], $param['ids']);

        if ($res['code'] == 0) {
            $res['msg'] = '操作成功';
        }

        return $res;
    }

    /**
     * 上传配置文件
     * @param $file
     * @return array
     */
    public function uploadCsvFile($file)
    {
        // 上传到本地服务器
        try {
            // 检测文件后缀
            $ext = $file->getOriginalExtension();
            if (!in_array($ext, ['csv'])) {
                return dataReturn(-3, '仅支持csv格式的文件');
            }

            $attachmentModel = new SysAttachment();
            $has = $attachmentModel->findOne(['sha1' => $file->hash()])['data'];
            if (!empty($has)) {
                return dataReturn(0, '上传成功', ['url' => $has['url']]);
            }

            // 存到本地
            $saveName = Filesystem::disk('public')->putFile('local', $file);
            $saveName = str_replace('\\', '/', $saveName);

            $root = str_replace('\\', '/', App::getRootPath());
            return dataReturn(0, '上传成功', [
                'url' => $root . 'public/storage/' . $saveName,
                'name' => rtrim($file->getOriginalName(), '.zip')
            ]);
        } catch (\Exception $e) {
            return dataReturn(-1, $e->getMessage());
        }
    }
}
