<?php

namespace strategy\reward;

use strategy\reward\impl\BalanceImpl;
use strategy\reward\impl\CouponImpl;

class RewardProvider
{
    protected $strategy;

    public function __construct($type)
    {
        switch ($type) {
            case 'balance':
                $this->strategy = new BalanceImpl();
                break;
            case 'coupon':
                $this->strategy = new CouponImpl();
                break;
        }
    }

    public function getStrategy()
    {
        return $this->strategy;
    }
}