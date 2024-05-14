<?php

namespace strategy\play;

use strategy\play\impl\ForFan;
use strategy\play\impl\Hash;
use strategy\play\impl\RandFan;
use strategy\play\impl\Random;
use strategy\play\impl\YiFan;

class PlayProvider
{
    protected $strategy;

    public function __construct($playId)
    {
        switch ($playId) {
            case 1:
                $this->strategy = new YiFan();
                break;
            case 2:
                $this->strategy = new Hash();
                break;
            case 3:
                $this->strategy = new Random();
                break;
            case 4:
                $this->strategy = new ForFan();
                break;
            case 5:
                $this->strategy = new RandFan();
                break;


        }
    }

    public function getStrategy()
    {
        return $this->strategy;
    }
}
