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

    protected int $sequence = 0;

    protected string $phase = self::PHASE_NEW;

    protected ?string $rseed = null;

    protected ?\Closure $random = null;

    protected array $messages = [];

    protected array $announcements = [];

    protected array $intermediateUpdates = [];

    protected bool $godMode = false;

    protected array $winner = [];

    public function __construct(string $playerClass, string $gameClass, mixed ...$elementClass)
    {
        $this->players = new PlayerCollection();
        $this->players->setClassName($playerClass);
        $this->game = new $gameClass(ElementContext::make($this, [Element::class, Space::class, Piece::class, ...$elementClass]));
        $this->players->setGame($this->game);
    }

    public function setRandomSeed($rseed): static
    {
        $this->rseed = $rseed;
        $this->random = function () {
            return mt_rand();
        };
        if ($this->game->getRandom()) {
            $this->game->setRandom($this->random);
        }

        return $this;
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

    public function setSequence(int $sequence, string $w = null)
    {
        match ($w) {
            '+' => $this->sqeuence += $sequence,
            '-' => $this->sequence -= $sequence,
            default => $this->sequence = $sequence,
        };

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

    public function getIntermediateUpdates(): array
    {
        return $this->intermediateUpdates;
    }
}
