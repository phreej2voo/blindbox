<?php

namespace strategy\play;

class PlayProgress
{
    public $playObj;

    public function __construct(PlayInterface $play)
    {
        $this->playObj = $play;
    }

    public function run($param)
    {
        $this->playObj->updateData($param); // 更新数据
        $this->playObj->award($param); // 发放特殊奖励
    }
}