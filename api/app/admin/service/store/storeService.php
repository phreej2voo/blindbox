<?php

namespace app\admin\service\store;

use app\model\user\User;
use app\model\store\Store;

class StoreService
{
    /**
     * 获取门店配置列表
     * @param $param
     * @return array
     */
    public function getStoreList($param)
    {
        /** @var  $storeModel */
        $storeModel = new Store();
        /** @var  $where */
        $where[] = ['id','>',0];
        isset($param['status']) && $where[] = ['status','=',$param['status']];
        /** @var  $list */
        $list = $storeModel->getPageList($param['limit'], $where, '*', 'sort desc');

        /** @var  $userModel */
        $userModel = new User();
        foreach ($list['data'] as &$row) {
            $row['user'] = '';
            if (!empty($row['uid'])) {
                /** @var  $userIds */
                $userIds = array_unique(array_filter(explode(',', $row['uid'])));
                /** @var  $userSet */
                $userSet = array_column(
                    $userModel->getAllList(['id' => $userIds], 'nickname')['data']->toArray(),
                    'nickname'
                );
                $row['user'] = implode(',', $userSet);
            }
        }

        return pageReturn($list);
    }
}