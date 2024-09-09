<?php

namespace Packages\BoardGame;


use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Player\Player;

abstract class GameCreator
{
    protected string $playerClass = Player::class;

    protected string $gameClass = BoardGame::class;

    abstract function createGame(BoardGame $game): void;

    public function create(): void
    {
        $gameManager = new GameManager($this->playerClass, $this->gameClass);

        $gameManager->getPlayers()->addPlayer([
            'id' => 1,
            'name' => 'test'
        ]);

        $this->createGame($gameManager->getGame());
    }
}
