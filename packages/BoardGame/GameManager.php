<?php

namespace Packages\BoardGame;


use Packages\BoardGame\Action\Action;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Element\Element;
use Packages\BoardGame\Board\Element\ElementContext;
use Packages\BoardGame\Board\Piece;
use Packages\BoardGame\Board\Space;
use Packages\BoardGame\Player\PlayerCollection;

class GameManager
{
    const PHASE_NEW  = 'NEW';
    const PHASE_STARTED  = 'STARTED';

    protected PlayerCollection $players;

    protected BoardGame $game;


    protected array $actions = [];

    protected string $phase = self::PHASE_NEW;

    public function __construct(string $playerClass, string $gameClass, mixed ...$elementClass)
    {
        $this->players = new PlayerCollection();
        $this->players->setClassName($playerClass);
        $this->game = new $gameClass(ElementContext::make($this, [Element::class, Space::class, Piece::class, ...$elementClass]));
        $this->players->setGame($this->game);
    }

    public function addAction(Action $action): static
    {
        $this->actions[] = $action;

        return $this;
    }

    public function setActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function getPhase(): string
    {
        return $this->phase;
    }

    public function getGame(): BoardGame
    {
        return $this->game;
    }

    public function getPlayers(): PlayerCollection
    {
        return $this->players;
    }
}
