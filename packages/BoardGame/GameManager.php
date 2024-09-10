<?php

namespace Packages\BoardGame;


use http\Exception\InvalidArgumentException;
use http\Exception\RuntimeException;
use Packages\BoardGame\Action\Action;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Element\Element;
use Packages\BoardGame\Board\Element\ElementContext;
use Packages\BoardGame\Board\Piece;
use Packages\BoardGame\Board\Space;
use Packages\BoardGame\Concerns\WithPhaseError;
use Packages\BoardGame\Player\PlayerCollection;

class GameManager
{
    use WithPhaseError;
    const PHASE_NEW  = 'NEW';
    const PHASE_STARTED  = 'STARTED';
    const PHASE_FINISHED  = 'FINISHED';

    protected array $flows = [];

    protected array $flowState = [];

    protected PlayerCollection $players;

    protected BoardGame $game;

    protected array $settings = [];

    protected array $actions = [];

    protected string $phase = self::PHASE_NEW;

    protected ?string $rseed = null;

    protected ?\Closure $random = null;

    protected array $messages = [];

    protected array $announcements = [];

    protected array $intermediateUpdates = [];

    public function __construct(string $playerClass, string $gameClass, mixed ...$elementClass)
    {
        $this->players = new PlayerCollection();
        $this->players->setClassName($playerClass);
        $this->game = new $gameClass(ElementContext::make($this, [Element::class, Space::class, Piece::class, ...$elementClass]));
        $this->players->setGame($this->game);
    }

    public function start(): static
    {
        $this->throwPhaseError('无法再次开始。', $this->phase);

        if ($this->players->isEmpty()) throw new InvalidArgumentException("没有玩家");

        $this->phase = self::PHASE_STARTED;
        $this->players->setCurrent($this->players->first());
        $this->flowState = [
            [
                'stack' => [],
                'currentPosition' => $this->players->getCurrentPosition(),
            ]
        ];

        return $this;
    }

    public function play()
    {
        if ($this->phase === self::PHASE_FINISHED) return;
        if ($this->phase !== self::PHASE_STARTED) throw new RuntimeException('游戏尚未开始');

    }

    public function addAction(\Closure $action): static
    {
        $this->actions[] = $action;

        return $this;
    }

    public function setActions(array $actions): static
    {
        $this->actions = $actions;

        return $this;
    }

    public function setSetting(string $key, mixed $value): static
    {
        $this->settings[$key] = $value;

        return $this;
    }

    public function setRandomSeed(string $seed): static
    {
        $this->rseed = $seed;
        $this->random = function () {
            return mt_rand(0, 1000);
        };

        if ($this->game->getRandom()) {
            $this->game->setRandom($this->random);
        }

        return $this;
    }

    public function getRandom(): \Closure|null
    {
        return $this->random;
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
