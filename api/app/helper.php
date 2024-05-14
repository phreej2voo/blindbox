<?php
/**
 * 生成分组奖品code
 * code 编码形如 20230713001001 20230713表示时间，001表示是第几个任务分组的 001代表该分组的第几个
 * @param $groupId
 * @param $rewardIndex
 * @return string
 */
function makeTaskCode($groupId, $rewardIndex)
{
    return makeTaskCodeSelf($groupId, $rewardIndex);
}

/**
 * 生成或解码用户的推广id
 * @param $userId
 * @param $type 1:编码 2:解码
 * @return false|string
 */
function shareCode($userId, $type = 1)
{
    return shareCodeSelf($userId, $type);
}

/**
 * 格式化错误信息
 * @param Exception $e
 * @return string
 */
function formatErrMsg(\Exception $e)
{
    return $e->getFile() . '|' . $e->getLine() . '|' . $e->getMessage();
}