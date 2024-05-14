<?php
namespace app\admin\service\marketing;

use app\admin\validate\RollValidate;
use app\model\marketing\Roll;
use app\model\marketing\RollDetail;
use app\model\marketing\RollUser;
use think\exception\ValidateException;
use think\facade\Db;

class RollService
{
    protected $rollModel;
    protected $rollDetail;

    public function __construct()
    {
        $this->rollModel = new Roll();
        $this->rollDetail = new RollDetail();
    }

    /**
     * 获取福利房列表
     * @param $param
     * @return array
     */
    public function getRollList($param)
    {
        $where = [];
        if (!empty($param['title'])) {
            $where[] = ['title', 'like', '%' . $param['title'] . '%'];
        }

        return $this->rollModel->getPageList($param['limit'], $where);
    }

    /**
     * 添加福利房
     * @param $param
     * @return array
     */
    public function addRoll($param)
    {
        try {
            validate(RollValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-2, $e->getError());
        }

        $res = $this->checkParam($param);
        if ($res['code'] != 0) {
            return $res;
        }

        if ($param['type'] == 1) {
            $param['limit_start_time'] = $param['tips'][0];
            $param['limit_end_time'] = $param['tips'][1];
        }
        unset($param['tips']);

        Db::startTrans();
        try {

            $userInfo = getUserInfo();

            $reward = $param['reward'];
            unset($param['reward']);

            $param['is_official'] = 1;
            $param['user_id'] = $userInfo['id'];
            $param['username'] = $userInfo['nickname'];
            $param['avatar'] = request()->domain() . '/static/images/login/login-icon.png';
            $param['create_time'] = now();

            $id = $this->rollModel->insertGetId($param);

            foreach ($reward as $key => $vo) {
                $reward[$key]['roll_id'] = $id;
                $reward[$key]['create_time'] = now();
            }

            $this->rollDetail->insertAll($reward);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
            return dataReturn(-5, $e->getMessage());
        }

        return dataReturn(0, '添加成功');
    }

    /**
     * 编辑福利房
     * @param $param
     * @return array
     */
    public function editRoll($param)
    {
        try {
            validate(RollValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(-2, $e->getError());
        }

        $res = $this->checkParam($param);
        if ($res['code'] != 0) {
            return $res;
        }

        if ($param['type'] == 1) {
            $param['limit_start_time'] = $param['tips'][0];
            $param['limit_end_time'] = $param['tips'][1];
        }
        unset($param['tips']);

        Db::startTrans();
        try {

            $userInfo = getUserInfo();

            $reward = $param['reward'];
            unset($param['reward']);

            $param['is_official'] = 1;
            $param['user_id'] = $userInfo['id'];
            $param['username'] = $userInfo['nickname'];
            $param['avatar'] = request()->domain() . '/static/images/login/login-icon.png';
            $param['update_time'] = now();

            $this->rollModel->where('id', $param['id'])->update($param);

            $this->rollDetail->where('roll_id', $param['id'])->delete();
            foreach ($reward as $key => $vo) {
                $reward[$key]['roll_id'] = $param['id'];
                $reward[$key]['create_time'] = now();
            }

            $this->rollDetail->insertAll($reward);

            Db::commit();
        } catch (\Exception $e) {
            Db::rollback();
        }

        return dataReturn(0, '修改成功');
    }

    /**
     * 关闭福利房
     * @param $id
     * @return array
     */
    public function delRoll($id)
    {
        return $this->rollModel->updateById(['status' => 4], $id);
    }

    /**
     * 获取奖品详情
     * @param $id
     * @return array
     */
    public function getRollDetail($id)
    {
        return $this->rollDetail->getAllList(['roll_id' => $id]);
    }

    /**
     * 获取参与详情
     * @param $id
     * @return array
     */
    public function getJoinDetail($id)
    {
        return (new RollUser())->getAllList(['roll_id' => $id]);
    }

    protected function checkParam($param)
    {
        if ($param['type'] == 1 && (empty($param['tips']) || empty($param['limit_amount']))) {
            return dataReturn(-3, '请输入限制条件');
        }

        if ($param['type'] == 2 && empty($param['password'])) {
            return dataReturn(-4, '请设置房间密码');
        }

        if (empty($param['reward'])) {
            return dataReturn(-5, '请设置奖品');
        }

        return dataReturn(0);
    }
}