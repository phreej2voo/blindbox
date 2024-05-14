<?php
namespace app\api\service\marketing;

use app\api\validate\shop\RollValidate;
use app\model\goods\Goods;
use app\model\marketing\Roll;
use app\model\marketing\RollDetail;
use app\model\marketing\RollReward;
use app\model\marketing\RollUser;
use app\model\order\Order;
use app\model\user\User;
use app\model\user\UserBoxHot;
use app\model\user\UserBoxLog;
use think\exception\ValidateException;
use think\facade\Db;
use think\facade\Log;
use utils\LuaScript;

class RollService
{
    protected $rollModel;
    protected $rollDetailModel;

    public function __construct()
    {
        $this->rollModel = new Roll();
        $this->rollDetailModel = new RollDetail();
    }

    /**
     * 获取roll房列表
     * @param $param
     * @return array
     */
    public function getRollList($param)
    {
        $list = $this->rollModel->field('id,type,is_official,username,avatar,title,hot')->where('status', 1)->paginate($param['limit']);

        $list->each(function ($item) {

            $item->num = $this->rollDetailModel->where('roll_id', $item->id)->count('id');
            $item->award = $this->rollDetailModel->where('roll_id', $item->id)->limit(3)->column('image');
            $item->recovery_price = $this->rollDetailModel->where('roll_id', $item->id)->sum('recovery_price');

            return $item;
        });

        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取roll房详情
     * @param $rollId
     * @return array
     */
    public function getRollDetail($rollId)
    {
        $info = $this->rollModel->field('id,type,username,avatar,title,hot,desc,reward_time')
            ->with(['goods', 'user'])
            ->where('status', 1)
            ->where('id', $rollId)->find();
        if (empty($info)) {
            return dataReturn(-2, '房间信息异常');
        }

        $info['reward_time'] = date('Y-m-d H:i', strtotime($info['reward_time']));
        foreach ($info['user'] as $key => $vo) {
            $info['user'][$key]['username'] = secureName($vo['username']);
        }

        return dataReturn(0, 'success', $info);
    }

    /**
     * 加入roll房
     * @param $param
     * @return array
     */
    public function joinRoll($param)
    {
        $info = $this->rollModel->findOne([
            'id' => $param['id'],
            'status' => 1
        ])['data'];
        if (empty($info)) {
            return dataReturn(-2, '福利房信息错误');
        }

        $userInfo = getUserInfo();
        // 检测加入标准
        if ($info['type'] == 1) { // 大佬房

            // 限定范围内消费
            $amount = (new Order())->whereBetween('create_time', [$info['limit_start_time'], $info['limit_end_time']])
                ->where('pay_status', 2)->where('user_id', $userInfo['id'])->sum('pay_price');

            if ($amount < $info['limit_amount']) {
                return dataReturn(-4, $info['limit_start_time'] . '~' . $info['limit_end_time'] .
                    '累计消费金额不足' . $info['limit_amount'] . '，无法加入');
            }

        } else { // 密码房
            if ($param['password'] != $info['password']) {
                return dataReturn(-3, '密码错误');
            }
        }

        $rollUserModel = new RollUser();
        // 检测是否加入过了
        $has = $rollUserModel->where('roll_id', $param['id'])->where('user_id', $userInfo['id'])->find();
        if (!empty($has)) {
            return dataReturn(-6, '您已加入，无需重复加入');
        }

        $experience = (new User())->field('experience')->where('id', $userInfo['id'])->find()->experience;

        // 加入房间
        $rollUserModel->insertOne([
            'roll_id' => $param['id'],
            'user_id' => $userInfo['id'],
            'username' => $userInfo['nickname'],
            'avatar' => $userInfo['avatar'],
            'hot' => $experience,
            'create_time' => now()
        ]);

        $this->rollModel->where('id', $info['id'])->inc('hot', $experience)->update();

        return dataReturn(0, '加入成功');
    }

    /**
     * 创建roll房
     * @param $param
     * @return array
     */
    public function createRoll($param)
    {
        try {
            validate(RollValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-2, $e->getError());
        }

        if (strtotime($param['reward_time']) < now()) {
            return dataReturn(-3, '开奖时间有误');
        }

        $userInfo = getUserInfo();

        $luaScript = new LuaScript();
        $key = 'box_operate_' . $userInfo['id'];
        $luaScript->lock($key);

        Db::startTrans();
        try {

            $putGoods = [];
            foreach ($param['award'] as $vo) {
                $putGoods[] = $vo['goods_id'];
            }

            $userBoxHotModel = new UserBoxHot();
            $userBox = $userBoxHotModel->field('goods_id,count(*) as num')
                ->where('user_id', $userInfo['id'])
                ->whereIn('goods_id', $putGoods)->group('goods_id')
                ->select()->toArray();
            if (empty($userBox)) {
                throw new \Exception('选择的奖品有误');
            }

            $goods2Num = array_column($userBox, 'num', 'goods_id');
            foreach ($param['award'] as $vo) {
                if (($vo['num'] <= 0) || ($vo['num'] > $goods2Num[$vo['goods_id']])) {
                    throw new \Exception('选择的奖品数量有误');
                }
            }

            $goodsList = (new Goods())->field('id,recovery_price')->whereIn('id', $putGoods)->select()->toArray();
            $goodsId2Info = array_column($goodsList, 'recovery_price', 'id');

            $id = $this->rollModel->insertGetId([
                'type' => 2,
                'is_official' => 2,
                'user_id' => $userInfo['id'],
                'username' => $userInfo['nickname'],
                'avatar' => $userInfo['avatar'],
                'title' => $param['title'],
                'desc' => $param['desc'],
                'password' => $param['password'],
                'reward_time' => $param['reward_time'],
                'status' => 1,
                'create_time' => now()
            ]);

            $detail = [];
            $boxHotModel = new UserBoxHot();
            $boxLogModel = new UserBoxLog();
            foreach ($param['award'] as $vo) {

                $box = $userBoxHotModel->where('goods_id', $vo['goods_id'])->where('user_id', $userInfo['id'])
                    ->limit($vo['num'])->select()->toArray();
                $uuids = [];
                $ids = [];
                foreach ($box as $v) {
                    $detail[] = [
                        'roll_id' => $id,
                        'goods_id' => $v['goods_id'],
                        'goods_name' => $v['goods_name'],
                        'image' => $v['goods_image'],
                        'recovery_price' => $goodsId2Info[$vo['goods_id']],
                        'box_data' => json_encode($v),
                        'create_time' => now()
                    ];

                    $ids[] = $v['id'];
                    $uuids[] = $v['uuid'];
                }

                $boxHotModel->whereIn('id', $ids)->delete();
                $boxLogModel->whereIn('uuid', $uuids)->update([
                    'status' => 5, // roll房赠送
                    'update_time' => now()
                ]);
            }

            $this->rollDetailModel->insertAll($detail);

            Db::commit();
            $luaScript->release($key);
        } catch (\Exception $e) {
            Db::rollback();
            Log::error('创建房间失败,' . $e->getFile() . '|' . $e->getLine() . '|' . $e->getMessage());
            $luaScript->release($key);
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '创建成功');
    }

    /**
     * 获取我的奖品
     * @return array
     */
    public function getMyGoods()
    {
        $userInfo = getUserInfo();

        $goodsList = (new UserBoxHot())->with(['orderRecordDetail'])
            ->field("goods_id,goods_name,goods_image,count('id') as num,record_detail_id")
            ->where('user_id', $userInfo['id'])
            ->group('goods_id')->select()->toArray();

        $goodIds = [];
        foreach ($goodsList as $vo) {
            if ($vo['record_detail_id'] == 0) {
                $goodIds[] = $vo['goods_id'];
            }
        }

        $goodsInfo = (new Goods())->getAllList([
            ['id', 'in', $goodIds]
        ], 'id,recovery_price')['data']->toArray();
        $goods2Info = array_column($goodsInfo, 'recovery_price', 'id');

        foreach ($goodsList as $key => $vo) {
            if ($vo['record_detail_id'] == 0) {
                $goodsList[$key]['recovery_price'] = $goods2Info[$vo['goods_id']];
            }
            unset($goodsList[$key]['record_detail_id']);
        }

        return dataReturn(0, 'success', $goodsList);
    }

    /**
     * 我的中奖记录
     * @param $param
     * @return array
     */
    public function getReward($param)
    {
        $userInfo = getUserInfo();

        return (new RollReward())->getPageList($param['limit'], [
            'user_id' => $userInfo['id']
        ], 'roll_name,goods_name,goods_image,create_time');
    }

    /**
     * 参与记录
     * @param $param
     * @return array
     */
    public function getJoinLog($param)
    {
        $userInfo = getUserInfo();
        $where = [];
        if (!empty($param['status'])) {
            $where[] = ['r.status', '=', $param['status']];
        }

        $list = (new RollUser())->alias('u')->field('u.roll_id,r.title,r.status,r.avatar,u.create_time')
            ->leftJoin([Roll::getTable() => 'r'], 'u.roll_id = r.id')
            ->where($where)
            ->where('u.user_id', $userInfo['id'])
            ->order('u.id desc')
            ->paginate($param['limit']);
        return dataReturn(0, 'success', $list);
    }
}