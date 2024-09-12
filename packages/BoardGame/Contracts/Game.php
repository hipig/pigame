<?php

namespace Packages\BoardGame\Contracts;

use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Player\Player;

interface Game
{
    /**
     * 玩家类定义
     *
     * @return string
     */
    public function player(): string;

    /**
     * 游戏类定义
     *
     * @return string
     */
    public function game(): string;

    /**
     * 游戏数据定义
     *
     * @param BoardGame $game
     * @return void
     */
    public function board(BoardGame $game): void;

    /**
     * 游戏动作定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function actions(BoardGame $game): array;

    /**
     * 游戏流程定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function flow(BoardGame $game): array;

    /**
     * 游戏子流程定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function subFlow(BoardGame $game): array;
}