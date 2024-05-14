<?php

namespace strategy\pay;

use strategy\pay\impl\AlipayImpl;
use strategy\pay\impl\BalancePayImpl;
use strategy\pay\impl\CardBalancePayImpl;
use strategy\pay\impl\ExpressBalancePayImpl;
use strategy\pay\impl\IntegralPayImpl;
use strategy\pay\impl\WechatPayImpl;
use think\Exception;

class PayProvider
{
    protected $strategy;

    public function __construct($type)
    {
        switch ($type) {
            case 1:
                $this->strategy = new WechatPayImpl();
                break;
            case 2:
                throw new Exception("请选择正确的支付方式");
                $this->strategy = new AlipayImpl();
                break;
            case 3:
                $this->strategy = new IntegralPayImpl();
                break;
            case 4: // 余额支付盲盒抽奖
                $this->strategy = new BalancePayImpl();
                break;
            case 40: // 余额支付邮费
                $this->strategy = new ExpressBalancePayImpl();
                break;
            case 41: // 余额支付会员卡
                $this->strategy = new CardBalancePayImpl();
                break;
        }
    }

    public function getStrategy()
    {
        return $this->strategy;
    }
}
