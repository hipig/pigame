<?php

namespace App\Games\Bzmm;


class Game
{
    protected string $name = 'bzmm';

    /**
     * 初始化数据
     * @return array
     */
    public function setup(): array
    {
        return [];
    }

    public function phases()
    {
        return [
            'draw' => '抽牌',
            'play' => '出牌',
        ];
    }
}