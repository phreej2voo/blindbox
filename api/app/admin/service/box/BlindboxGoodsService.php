<?php

namespace app\admin\service\box;

use app\model\box\Blindbox;
use app\model\box\BlindboxBoxes;
use app\model\box\BlindboxBoxesConfig;
use app\model\box\BlindboxBoxesDetail;
use app\model\box\BlindboxGoods;
use think\Exception;
use think\facade\Db;
use utils\MyRedis;

class BlindboxGoodsService
{
    /**
     * 获取盲盒详情列表
     * @return array
     */
    public function getBlindboxGoodslList($blindboxId)
    {

        $blindbox = new Blindbox();
        $res = $blindbox->find($blindboxId);
        $blindboxGoodsModel = new BlindboxGoods();
        $result = $blindboxGoodsModel->getAllList([
            'blindbox_id' => $blindboxId
        ], '*', 'id asc');

        if ($res['play_id'] == 5) {
            return $result;
        }
        $tmpArr = [];
        foreach ($result['data'] as $v) {
            $tmpArr[$v['tag_id']][] = $v;
        }
        $blindboxboxesconfig = new BlindboxBoxesConfig();
        $resConfig = $blindboxboxesconfig->where(['blindbox_id'=>$blindboxId,'play_id'=>$res['play_id']])->select();
        foreach ($resConfig as $k=>$v) {
            $resConfig[$k]['goods'] = $tmpArr[$v['tag_id']];
        }
        $result['data'] = $resConfig;
        return $result;
    }

    public function getBlindboxGoodslListOld($blindboxId)
    {
        $blindboxGoodsModel = new BlindboxGoods();
        return $blindboxGoodsModel->getAllList([
            'blindbox_id' => $blindboxId
        ], '*', 'id asc');
    }

    /**
     * 盲盒配置设置
     * @param $param
     * @return array
     */
    public function editBlindboxGoods($param)
    {
        $str = <<<EOL
// 传递修改的参数
    {
    "data": [
        {
            "picture_image": "2",
            "probability": "20",
            "id": 1
        },
        {
            "picture_image": "10",
            "probability": "80",
            "id": 2
        }
    ],
    "blindbox_id": 14,
    "play_id": 4
}
EOL;

        if (5 == $param['play_id']) {
            return dataReturn(-1, '无限赏盲盒不支持修改');
        }
        $update = [];
        foreach ($param['data'] as $vo) {
            if (empty($vo['id'])) {
                return dataReturn(-1, 'id参数错误');
            }
            if (empty($vo['probability']) || $vo['probability'] <=0) {
                return dataReturn(-1, '中奖概率不能设置为0');
            }
            $update[$vo['id']] = ['picture_image'=>$vo['picture_image'],'probability'=>$vo['probability']];
        }
        if (array_sum(array_column($update,'probability')) != 100) {
            return dataReturn(-1, '赏种的中奖概率不是100%');
        }
        $model = new BlindboxBoxesConfig;
        foreach ($update as $id=>$data) {
            $model->updateByWhere($data,['id'=>$id,'blindbox_id'=>$param['blindbox_id']]);
        }
        return dataReturn(0, '设置成功');

    }
    public function addBlindboxGoodsOld($param)
    {
        $res = $this->checkData($param);
        if ($res['code'] != 0) {
            return $res;
        }

        Db::startTrans();
        try {

            $blindboxGoodsModel = new BlindboxGoods();
            $blindboxGoodsModel->where('blindbox_id', $param['blindbox_id'])->delete();

            $insertData = [];
            $stockInOneBox = 0;
            foreach ($param['data'] as $vo) {
                $insertData[] = [
                    'blindbox_id' => $vo['blindbox_id'],
                    'tag_id' => $vo['tag_id'],
                    'goods_id' => $vo['goods_id'],
                    'goods_name' => $vo['goods_name'],
                    'goods_image' => $vo['goods_image'],
                    'price' => $vo['price'],
                    'recovery_price' => $vo['recovery_price'],
                    'stock' => $vo['stock'],
                    'probability' => $vo['probability'],
                    'create_time' => now()
                ];

                $stockInOneBox += $vo['stock'];
            }

            if (!empty($insertData)) {
                $blindboxGoodsModel->insertAll($insertData);

                // 无限赏
                if ($param['play_id'] == 2) {
                    $this->onlyMakeBox($insertData, $param['blindbox_id'], $stockInOneBox);
                }
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '设置成功');
    }
    /**
     * 添加盲盒商品
     * @param $param
     * @return array
     */
    public function addBlindboxGoods($param)
    {
        $res = $this->checkDataNew($param);
        if ($res['code'] != 0) {
            return $res;
        }

        Db::startTrans();
        try {
            // 无限盲盒
            $playId = $param['play_id'];
            if (in_array($playId,[4,5])){
                $blindboxBoxesConfig =  new BlindboxBoxesConfig();
                $insertData = $res['data'][$playId];
                $blindboxBoxesConfig->insertAll($res['data']['config']);
            } else {
                throw new Exception('该玩法暂不支持');
            }
            // 添加盲盒商品
            if (!empty($insertData)) {
                $blindboxGoodsModel = new BlindboxGoods();
                $blindboxGoodsModel->insertAll($insertData);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '设置成功');
    }

    /**
     * 生成箱子
     * @param $goodsList
     * @param $blindboxId
     * @param $stockInOneBox
     * @return void
     */
    protected function onlyMakeBox($goodsList, $blindboxId, $stockInOneBox)
    {
        try {

            $blindboxBoxes = new BlindboxBoxes();
            $blindboxBoxesDetail = new BlindboxBoxesDetail();

            $boxInfo = $blindboxBoxes->where('blindbox_id', $blindboxId)->find();
            if (!empty($boxInfo)) {
                $blindboxBoxes->where('blindbox_id', $blindboxId)->delete();
                $blindboxBoxesDetail->where('blindbox_id', $blindboxId)->delete();
            }

            $blindboxBoxes->insert([
                'box_no' => 1,
                'blindbox_id' => $blindboxId,
                'sales_cost_price' => 0,
                'sales_amount' => 0,
                'profit_loss_amount' => 0,
                'sales' => 0,
                'stock' => $stockInOneBox,
                'create_time' => now()
            ]);

            $detailMap = [];
            foreach ($goodsList as $vo) {
                $detailMap[] = [
                    'blindbox_id' => $blindboxId,
                    'box_id' => 1,
                    'tag_id' => $vo['tag_id'],
                    'blindbox_goods_id' => $vo['goods_id'],
                    'sales' => 0,
                    'stock' => $vo['stock'],
                    'create_time' => now()
                ];
            }

            $blindboxBoxesDetail->insertAll($detailMap);
        } catch (\Exception $e) {

        }
    }

    public function makeBlindboxGoodsBoxOld($param)
    {
        if ($param['num'] <= 0) {
            return dataReturn(-3, '箱子数量必须大于0');
        }

        Db::startTrans();
        try {

            // 箱子内商品的基础信息
            $blindboxGoodsModel = new BlindboxGoods();
            $goodsList = $blindboxGoodsModel->where('blindbox_id', $param['blindbox_id'])->select()->toArray();
            if (empty($goodsList)) {
                throw new \Exception('该盲盒内没有奖品,请先添加并保存奖品');
            }
            $stockInOneBox = $blindboxGoodsModel->where('blindbox_id', $param['blindbox_id'])->where('tag_id', '>', 0)->sum('stock');

            $blindboxBoxes = new BlindboxBoxes();
            // 初始箱号
            $startNo = $blindboxBoxes->where('blindbox_id', $param['blindbox_id'])->max('box_no');

            $blindboxBoxesDetail = new BlindboxBoxesDetail();
            $redisObject = (new MyRedis())->getObject();
            for ($i = 0; $i < $param['num']; $i++) {

                $startNo = $startNo + 1;
                $blindboxBoxes->insert([
                    'box_no' => $startNo,
                    'blindbox_id' => $param['blindbox_id'],
                    'sales_cost_price' => 0,
                    'sales_amount' => 0,
                    'profit_loss_amount' => 0,
                    'sales' => 0,
                    'stock' => $stockInOneBox,
                    'create_time' => now()
                ]);

                $redisObject->set('blindbox:' . $param['blindbox_id'] . ':' . $startNo, $stockInOneBox);

                $detailMap = [];
                foreach ($goodsList as $vo) {
                    $detailMap[] = [
                        'blindbox_id' => $param['blindbox_id'],
                        'box_id' => $startNo,
                        'tag_id' => $vo['tag_id'],
                        'blindbox_goods_id' => $vo['goods_id'],
                        'sales' => 0,
                        'stock' => $vo['stock'],
                        'create_time' => now()
                    ];
                }

                $blindboxBoxesDetail->insertAll($detailMap);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0);
    }
    /**
     * 操作箱子
     * @param $param
     * @return array
     */
    public function makeBlindboxGoodsBox($param)
    {
        $blindbox = new Blindbox();
        $res = $blindbox->find($param['blindbox_id']);
        if (empty($res)) {
            return dataReturn(-3, '盲盒不存在');
        }

        if ($param['num'] <= 0) {
            return dataReturn(-3, '箱子数量必须大于0');
        }

        Db::startTrans();
        try {

            // 箱子内商品的基础信息
            $blindboxGoodsModel = new BlindboxGoods();
            $goodsList = $blindboxGoodsModel->where('blindbox_id', $param['blindbox_id'])->select()->toArray();
            if (empty($goodsList)) {
                throw new \Exception('该盲盒内没有奖品,请先添加并保存奖品');
            }
            $stockInOneBox = $blindboxGoodsModel->where('blindbox_id', $param['blindbox_id'])->sum('stock');


            $blindboxBoxes = new BlindboxBoxes();
            // 初始箱号
            $startNo = $blindboxBoxes->where('blindbox_id', $param['blindbox_id'])->max('box_no');

            // 判断盲盒类型
            if ($res['play_id'] == 5 && $startNo > 0) {
                throw new \Exception('无限赏盲盒不可以补箱');
            }

            $blindboxBoxesDetail = new BlindboxBoxesDetail();
            $redisObject = (new MyRedis())->getObject();
            for ($i = 0; $i < $param['num']; $i++) {

                $startNo = $startNo + 1;
                $blindboxBoxes->insert([
                    'box_no' => $startNo,
                    'blindbox_id' => $param['blindbox_id'],
                    'sales_cost_price' => 0,
                    'sales_amount' => 0,
                    'profit_loss_amount' => 0,
                    'sales' => 0,
                    'stock' => $stockInOneBox,
                    'create_time' => now()
                ]);

                $redisObject->set('blindbox:' . $param['blindbox_id'] . ':' . $startNo, $stockInOneBox);

                $detailMap = [];
                foreach ($goodsList as $vo) {
                    $detailMap[] = [
                        'blindbox_id' => $param['blindbox_id'],
                        'box_id' => $startNo,
                        'tag_id' => $vo['tag_id'],
                        'blindbox_goods_id' => $vo['goods_id'],
                        'sales' => 0,
                        'stock' => $vo['stock'],
                        'create_time' => now()
                    ];
                }

                $blindboxBoxesDetail->insertAll($detailMap);
            }

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0);
    }

    protected function checkDataNew($param)
    {
        $blindbox = new Blindbox();
        $res = $blindbox->where('play_id',$param['play_id'])->find($param['blindbox_id']);
        if (empty($res)) {
            return dataReturn(-3, '盲盒不存在');
        }
        // 4 潮玩赏 5无限赏
        $tmpArr = [];
        $config = [];
        $playId = $param['play_id'];
        if ($playId == 4){
            $totalProbability = 0;
            $tagIds = [];
            foreach ($param['data'] as $vo) {
                if (empty($vo['tag_id'])) {
                    return dataReturn(-10, '请选择奖品赏种');
                }
                if (empty($vo['probability']) || $vo['probability'] <=0) {
                    return dataReturn(-10, '奖品概率设置错误');
                }
                if (empty($vo['picture_image']) ) {
                    return dataReturn(-10, '封面图不能为空');
                }
                $totalProbability += $vo['probability'];
                $tagIds[] = $vo['tag_id'];
                if (empty($vo['goods'])) {
                    return dataReturn(-10, '赏品不能为空');
                }
                $goodIds = [];
                $stock = 0;
                foreach ($vo['goods'] as $v) {
                    if ($v['stock'] <= 0) {
                        return dataReturn(-10, '赏品库存不能小于0');
                    }
                    $tmpArr[] = [
                        'blindbox_id' => $param['blindbox_id'],
                        'tag_id' => $vo['tag_id'],
                        'goods_id' => $v['goods_id'],
                        'goods_name' => $v['goods_name'],
                        'goods_image' => $v['goods_image'],
                        'price' => $v['price'],
                        'recovery_price' => $v['recovery_price'],
                        'stock' => $v['stock'],
                        'probability' => $vo['probability'],
                        'create_time' => now()
                    ];
                    $goodIds[] = $v['goods_id'];
                    $stock += $v['stock'];
                }
                if (count(array_unique($goodIds)) != count($vo['goods'])) {
                    return dataReturn(-1, '不允许设置重复的赏品');
                }
                $config[] = [
                    'blindbox_id' => $param['blindbox_id'],
                    'tag_id' => $vo['tag_id'],
                    'play_id'=>$playId,
                    'probability' => $vo['probability'],
                    'create_time' => now(),
                    'picture_image'=>$vo['picture_image'],
                    'stock' => $stock,
                ];
            }
            if (count($param['data']) != count(array_unique($tagIds))) {
                return dataReturn(-1, '不允许设置重复的赏种');
            }
            if ($totalProbability != 100) {
                return dataReturn(-10, '概率总和必须为100');
            }
            /*$model = new BlindboxBoxesConfig();
            if ($model->where('blindbox_id',$param['blindbox_id'])->where('play_id',$playId)->find()) {
                return dataReturn(-10, '不能修改赏品信息');
            }*/
            return dataReturn(0,'',[$playId=>$tmpArr,'config'=>$config]);
        }  else if ($playId == 5) {
            $goodIds = [];
            foreach ($param['data'] as $vo) {
                $stock = 0;
                foreach ($vo['goods'] as $v) {
                    if ($v['stock'] <= 0) {
                        return dataReturn(-10, '赏品库存不能小于0');
                    }
                    $tmpArr[] = [
                        'blindbox_id' => $param['blindbox_id'],
                        'tag_id' => $vo['tag_id'],
                        'goods_id' => $v['goods_id'],
                        'goods_name' => $v['goods_name'],
                        'goods_image' => $v['goods_image'],
                        'price' => $v['price'],
                        'recovery_price' => $v['recovery_price'],
                        'stock' => $v['stock'],
                        'probability' => $vo['probability'],
                        'create_time' => now()
                    ];
                    $goodIds[] = $v['goods_id'];
                    $stock += $v['stock'];
                }
                $config[] = [
                    'blindbox_id' => $param['blindbox_id'],
                    'play_id'=>$playId,
                    'tag_id' => $vo['tag_id'] ?? 0,
                    'probability' => '',
                    'create_time' => now(),
                    'picture_image'=>'',
                    'stock' => $stock,
                ];
            }
            if (count($goodIds) != count(array_unique($goodIds))) {
                return dataReturn(-1, '不允许设置重复的赏品');
            }
            $model = new BlindboxBoxesConfig();
            if ($model->where(['blindbox_id'=>$param['blindbox_id'],'play_id'=>$playId])->find()) {
                return dataReturn(-10, '不能修改赏品信息');
            }
            return dataReturn(0,'',[$playId=>$tmpArr,'config'=>$config]);
        } else {
            return dataReturn(-3, '盲盒类型错误');
        }
    }
    /**
     * 数据校验
     * @param $param
     * @return array
     */
    protected function checkData($param)
    {
        $playId = $param['play_id'];
        $totalProbability = 0;
        $hasLast = false;
        if ($playId != 1 && $playId != 3) {
            $hasLast = true;
        }

        $goodsIds = [];
        foreach ($param['data'] as $vo) {
            if (($playId == 1 || $playId == 3) && ($vo['tag_id'] == 0)) {
                $hasLast = true;
            }

            if ($vo['tag_id'] != 0 && $vo['stock'] <= 0) {
                return dataReturn(-10, '库存不能小于0');
            }

            if ($vo['tag_id'] != 0 && empty($vo['tag_id'])) {
                return dataReturn(-10, '请选择奖品赏种');
            }

            if ($vo['tag_id'] != 0 && empty($vo['probability'])) {
                return dataReturn(-10, '奖品概率设置错误');
            }

            $totalProbability += $vo['probability'];

            $goodsIds[] = $vo['goods_id'];
        }

        if (count($param['data']) != count(array_unique($goodsIds))) {
            return dataReturn(-1, '不允许设置重复的商品');
        }

        if (!$hasLast) {
            return dataReturn(-2, '一番赏、全随机必须设置LAST奖品');
        }

        if ((string)$totalProbability != (string)100) {
            return dataReturn(-3, '总概率不是100%');
        }

        return dataReturn(0);
    }
}
