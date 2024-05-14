<?php

namespace strategy\reward;

interface RewardInterface
{
    public function getStatus($code, $userId, $status);

    public function receive($reward, $param, $source);

    public function getDetail($id);
}