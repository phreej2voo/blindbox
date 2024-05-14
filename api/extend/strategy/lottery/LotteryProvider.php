<?php

namespace strategy\lottery;

use strategy\lottery\impl\ForLotteryImpl;
use strategy\lottery\impl\RandLotteryImpl;
use strategy\lottery\impl\RandomLotteryImpl;

class LotteryProvider
{
    protected $strategy;

    public function __construct($playId)
    {
        switch ($playId) {
            case 1:
            case 2:
            case 3:
                $this->strategy = new RandomLotteryImpl($playId);
                break;
            case 4:
                $this->strategy = new ForLotteryImpl($playId);
                break;
            case 5:
                $this->strategy = new RandLotteryImpl($playId);
                break;

        }
    }

    public function getStrategy()
    {
        return $this->strategy;
    }
}
