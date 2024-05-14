<?php

namespace utils;

use app\model\box\BlindboxTag;
use app\model\system\SysSetting;
use strategy\sms\SmsProvider;
use strategy\store\StoreProvider;

class Tools
{
    public static function sendSms($param)
    {
        $info = getConfByType('sms');

        $sms = [
            'access_key_id' => $info['access_key_id'],
            'access_key_secret' => $info['access_key_secret'],
            'sign_name' => $info['sign_name'],
            'templateCode' => $info[$param['type']],
            'phone' => $param['phone']
        ];

        $smsProvider = new SmsProvider('ali');
        $sendParam = formatSmsData($sms);
        $res = $smsProvider->getStrategy()->send($sendParam);

        if ($res['code'] == 0) {
            // 记录5分钟
            cache($param['phone'] . '_' . $param['type'], json_decode($sendParam['code'], true)['code'], 300);
        }

        return $res;
    }

    public static function getPayWay()
    {
        // 支付方式开启情况
        $sysSettingModel = new SysSetting();
        $payWayMap = $sysSettingModel->getOpenWay()['data'];
        $payWay = '';
        if (isset($payWayMap['wechat_pay']) && $payWayMap['wechat_pay'] == 1) {
            $payWay = 'wechat_pay';
        } else if (isset($payWayMap['alipay']) && $payWayMap['alipay'] == 1) {
            $payWay = 'alipay';
        }

        return compact('payWayMap', 'payWay');
    }

    /**
     * 第三方存储
     * @param $storeWay
     * @param $file
     * @param $saveName
     * @return string
     */
    public static function storeOSS($storeWay, $saveName)
    {
        $storeConfigMap = config('shop.store_config');
        $config = getConfByType($storeConfigMap[$storeWay]);
        $provider = new StoreProvider($storeWay, $config);
        $path = app()->getRootPath() . 'public/storage/' . $saveName;

        $provider->getStrategy()->upload($path, $saveName);
        unlink($path);
        removeEmptyDir(dirname($path));
        $ossDomain = $config[config('shop.store_domain')[$storeWay]];
        if (strstr($ossDomain, 'http://') == false && strstr($ossDomain, 'https://') == false) {
            $ossDomain = 'https://' . $ossDomain;
        }

        return $ossDomain . '/' . $saveName;
    }

    /**
     * 金额转换
     * @param $num
     * @return mixed|string
     */
    public static function convert($num)
    {
        if ($num >= 100000000) {
            $num = round($num / 100000000, 1) . '亿+';
        } else if ($num >= 10000000) {
            $num = round($num / 10000000, 1) . '万+';
        } else if ($num >= 10000) {
            $num = round($num / 10000, 1) . '万+';
        } else if ($num >= 1000) {
            $num = round($num / 1000, 1) . '千+';
        }
        return $num;
    }

    /**
     * 根据平台选择支付
     * @param $payProvider
     * @param $platform
     * @param $orderParam
     * @return mixed
     */
    public static function payByPlatForm($payProvider, $platform, $orderParam)
    {
        if ($platform == 'miniapp') {
            $res = $payProvider->miniPay($orderParam);
        } else if ($platform == 'h5') {
            $res = $payProvider->wapPay($orderParam);
        } else {
            $res = $payProvider->appPay($orderParam);
        }

        return $res;
    }

    /**
     * 字符串脱敏
     * @param string $string
     * @return string
     */
    public static function desensitizeString(String $string)
    {
        if (strlen($string) >= 3) {
            return mb_substr($string, 0, 1) . '***' . mb_substr($string, -1);
        } else {
            return mb_substr($string, 0, 1) . '***';
        }
    }

    /**
     * 写入redis队列
     * @param $redis
     * @param $queue
     * @param $data
     * @param int $delay
     * @return mixed
     */
    public static function redisQueueSend($redis, $queue, $data, int $delay = 0)
    {
        $queueWaiting = '{redis-queue}-waiting';
        $queueDelay = '{redis-queue}-delayed';
        $now = time();
        $packageStr = json_encode([
            'id' => rand(),
            'time' => $now,
            'delay' => 0,
            'attempts' => 0,
            'queue' => $queue,
            'data' => $data
        ]);

        if ($delay) {
            return $redis->zAdd($queueDelay, $now + $delay, $packageStr);
        }

        return $redis->lPush($queueWaiting . $queue, $packageStr);
    }

    /**
     * 获取标签名称
     * @param $tagId
     * @return array
     */
    public static function getTagName($tagId)
    {
        try {

            $blindboxTag = (new BlindboxTag())->field('id,name')->where('status', 1)->cache(true, 3)->select();
            $tag2Name = [];
            foreach ($blindboxTag as $vo) {
                $tag2Name[$vo['id']] = $vo['name'];
            }

            $specialReward = config('shop.special_reward');

            if ($tagId <= 0) {
                $tagName = $specialReward[$tagId];
            } else {
                $tagName = $tag2Name[$tagId];
            }
        } catch (\Exception $e) {
            return dataReturn(-1, $e->getMessage(), '未知赏种');
        }

        return dataReturn(0, 'success', $tagName);
    }
}
