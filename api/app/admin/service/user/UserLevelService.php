<?php

namespace app\admin\service\user;

use app\admin\validate\UserLevelValidate;
use app\model\marketing\Coupon;
use app\model\user\User;
use app\model\user\UserLevel;
use app\model\user\UserLevelPresent;
use think\exception\ValidateException;
use think\facade\Db;

class UserLevelService
{
    /**
     * 用户等级列表
     * @param $param
     * @return array
     */
    public function getList($param)
    {
        $where[] = ['is_delete', '=', 1];
        if (!empty($param['title'])) {
           $where[] = ['title', 'like', '%' . $param['title'] . '%'];
        }

        $userLevelModel = new UserLevel();
        $list = $userLevelModel->with(['present'])->where($where)->order('level asc')->paginate($param['limit']);

        return pageReturn(dataReturn(0, 'success', $list));
    }

    /**
     * 添加用户等级
     * @param $param
     * @return array
     */
    public function addLevel($param)
    {
        try {
            validate(UserLevelValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        // 检测重复
        $userLevelModel = new UserLevel();
        $hasLevel = $userLevelModel->findOne(['level' => $param['level']], 'id')['data'];
        if (!empty($hasLevel)) {
            return dataReturn(-2, '该等级已经存在');
        }

        Db::startTrans();
        try {

            // 用户等级入库
            $levelId = $userLevelModel->insertGetId([
                'title' => $param['title'],
                'level' => $param['level'],
                'discount' => $param['discount'],
                'experience' => $param['experience'],
                'icon' => $param['icon'],
                'card_bg' => $param['card_bg'],
                'status' => $param['status'],
                'remark' => $param['remark'],
                'create_time' => now()
            ]);

            // 处理赠送
            $this->dealSend($param['present'], $levelId);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, '添加失败' . $e->getMessage() . '|' . $e->getLine());
        }

        return dataReturn(0, '添加成功');
    }

    /**
     * 编辑用户等级
     * @param $param
     * @return array
     */
    public function editLevel($param)
    {
        try {
            validate(UserLevelValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-1, $e->getError());
        }

        // 检测重复
        $userLevelModel = new UserLevel();
        $hasLevel = $userLevelModel->findOne([
            ['level', '=', $param['level']],
            ['id', '<>', $param['id']]
        ], 'id')['data'];
        if (!empty($hasLevel)) {
            return dataReturn(-2, '该等级已经存在');
        }

        Db::startTrans();
        try {

            // 用户等级入库
            $userLevelModel->where('id', $param['id'])->update([
                'title' => $param['title'],
                'level' => $param['level'],
                'discount' => $param['discount'],
                'experience' => $param['experience'],
                'icon' => $param['icon'],
                'card_bg' => $param['card_bg'],
                'status' => $param['status'],
                'remark' => $param['remark'],
                'update_time' => now()
            ]);

            // 处理赠送
            $this->dealSend($param['present'], $param['id']);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-10, '添加失败' . $e->getMessage() . '|' . $e->getLine());
        }

        return dataReturn(0, '添加成功');
    }

    /**
     * 删除用户等级
     * @param $id
     * @return array
     */
    public function delLevel($id)
    {
        $hasUsed = (new User())->findOne(['level_id' => $id])['data'];
        if (!empty($hasUsed)) {
            return dataReturn(-10, '该等级已经被使用，无法删除');
        }

        $userLevelModel = new UserLevel();
        $userLevelModel->updateById(['is_delete' => 2], $id);

        return dataReturn(0, '操作成功');
    }

    /**
     * 获取等级信息
     * @param $param
     * @return array
     */
    public function getLevelInfo($param)
    {
        $userLevelModel = new UserLevel();
        // 查询当前用户等级
        $userInfo = (new User())->findOne(['id' => $param['user_id']])['data'];
        if (empty($userInfo['level_id'])) {

            $levelInfo = (new UserLevel())->where('is_delete', 1)->order('level asc')->find();
            return dataReturn(0, 'success', [
                'user_experience' => $userInfo['experience'],
                'level_experience' => 0,
                'next_level_experience' => empty($levelInfo) ? 0 : $levelInfo['experience'],
                'icon' => '',
                'card_bg' => '',
                'level' => 0,
                'level_title' => '暂无',
                'present_list' => [],
            ]);
        }

        $levelInfo = $userLevelModel->findOne([
            'id' => $userInfo['level_id'],
            'is_delete' => 1,
            'status' => 1
        ])['data'];

        // 赠送
        $present = (new UserLevelPresent())->getAllList(['level_id' => $levelInfo['id']])['data'];
        $presentList = [];
        foreach ($present as $vo) {
            if ($vo['present_num'] == 0) {
                continue;
            }

            // 优惠券
            if ($vo['type'] == 1) {
                $presentList[] = [
                    'type' => 'coupon',
                    'info' => (new Coupon())->findOne([
                        'id' => $vo['present_id']
                    ], 'name,type,amount,discount')['data'],
                    'num' => $vo['present_num']
                ];
            } else if ($vo['type'] == 2) { // 道具卡
                $presentList[] = [
                    'type' => 'prop',
                    'info' => [
                        'name' => '重抽卡'
                    ],
                    'num' => $vo['present_num']
                ];
            }
        }

        // 距离下一个等级的经验
        $nextLevel = (new UserLevel())->where('level', '>', $levelInfo['level'])->where('is_delete', 1)->order('level asc')->find();

        $return = [
            'user_experience' => $userInfo['experience'],
            'level_experience' => $levelInfo['experience'],
            'next_level_experience' => empty($nextLevel) ? 0 : $nextLevel['experience'],
            'icon' => $levelInfo['icon'],
            'card_bg' => $levelInfo['card_bg'],
            'level' => $levelInfo['level'],
            'level_title' => $levelInfo['title'],
            'present_list' => $presentList,
        ];

        return dataReturn(0, 'success', $return);
    }

    /**
     * 处理赠送逻辑
     * @param $param
     * @param $levelId
     * @return array
     */
    protected function dealSend($param, $levelId)
    {
        $userLevelPresentModel = new UserLevelPresent();
        $userLevelPresentModel->where(['level_id' => $levelId])->delete();

        $data = [];
        if (!empty($param['coupon'])) {
            $data[] = [
                'level_id' => $levelId,
                'type' => 1,
                'present_id' => $param['coupon'],
                'present_num' => $param['coupon_num'],
                'create_time' => now()
            ];
        }

        if (!empty($param['prop'])) {
            $data[] = [
                'level_id' => $levelId,
                'type' => 2,
                'present_id' => $param['prop'],
                'present_num' => $param['prop_num'],
                'create_time' => now()
            ];
        }

        if (!empty($data)) {
            $userLevelPresentModel->insertAll($data);
        }

        return dataReturn(0);
    }
}