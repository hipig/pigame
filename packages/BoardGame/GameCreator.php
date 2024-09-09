<?php

namespace Packages\BoardGame;


use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Player\Player;

abstract class GameCreator
{
    protected string $playerClass = Player::class;

    protected string $gameClass = BoardGame::class;

    abstract function createGame(BoardGame $game);

    public function create()
    {
        $gameManager = new GameManager($this->playerClass, $this->gameClass);

        
    }
}
