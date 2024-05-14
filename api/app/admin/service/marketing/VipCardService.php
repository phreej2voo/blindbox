<?php

namespace app\admin\service\marketing;

use app\admin\validate\VipCardValidate;
use app\model\marketing\VipCard;
use app\model\marketing\VipCardOrder;
use app\model\marketing\VipCardReceived;
use think\exception\ValidateException;

class VipCardService
{
    /**
     * 获取会员卡列表
     * @param $param
     * @return array
     */
    public function getCardList($param)
    {
        $where = [];
        if (!empty($param['title'])) {
            $where[] = ['title', 'like', '%' . $param['title'] . '%'];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        if (!empty($param['type'])) {
            $where[] = ['type', '=', $param['type']];
        }

        return (new VipCard())->getPageList($param['limit'], $where);
    }

    /**
     * 添加会员卡
     * @param $param
     * @return array
     */
    public function addCard($param)
    {
        try {
            validate(VipCardValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(0, $e->getError());
        }

        $vipCardModel = new VipCard();
        $has = $vipCardModel->findOne(['title' => $param['title']])['data'];
        if (!empty($has)) {
            return dataReturn(-1, '该会员卡已经存在');
        }

        $param['equity'] = json_encode($param['equity']);
        $param['create_time'] = now();

        return $vipCardModel->insertOne($param);
    }

    /**
     * 编辑会员卡
     * @param $param
     * @return array
     */
    public function editCard($param)
    {
        try {
            validate(VipCardValidate::class)->check($param);
        } catch (ValidateException $e) {
            return dataReturn(0, $e->getError());
        }

        $vipCardModel = new VipCard();
        $has = $vipCardModel->findOne([
            ['title', '=', $param['title']],
            ['id', '<>', $param['id']],
        ])['data'];
        if (!empty($has)) {
            return dataReturn(-1, '该会员卡已经存在');
        }

        $param['equity'] = json_encode($param['equity']);
        $param['update_time'] = now();

        return $vipCardModel->updateById($param, $param['id']);
    }

    /**
     * 作废会员卡
     * @param $id
     * @return array
     */
    public function delCard($id)
    {
        $vipCardModel = new VipCard();
        return $vipCardModel->updateById(['status' => 2], $id);
    }

    /**
     * 获取会员卡信息
     * @param $id
     * @return array
     */
    public function getInfo($id)
    {
        return (new VipCard())->findOne(['id' => $id]);
    }

    /**
     * 获取购买日志
     * @param $param
     * @return array
     */
    public function getBuyLog($param)
    {
        $where = [];
        if (!empty($param['user_id'])) {
            $where[] = ['user_id', '=', $param['user_id']];
        }

        if (!empty($param['card_type'])) {
            $where[] = ['card_type', '=', $param['card_type']];
        }

        if (!empty($param['status'])) {
            $where[] = ['status', '=', $param['status']];
        }

        $list = (new VipCardOrder())->where($where)->with(['card'])->paginate($param['limit']);
        return dataReturn(0, 'success', $list);
    }

    /**
     * 获取领取日志
     * @param $param
     * @return array
     */
    public function getReceiveLog($param)
    {
        $where = [];
        if (!empty($param['receive_date'])) {
            $where[] = ['receive_date', '=', $param['receive_date']];
        }

        if (!empty($param['user_id'])) {
            $where[] = ['user_id', '=', $param['user_id']];
        }

        return (new VipCardReceived())->getPageList($param['limit'], $where);
    }
}