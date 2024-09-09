<?php

namespace Packages\BoardGame\Board\Element;

use Packages\BoardGame\GameManager;
use Packages\BoardGame\Player\Player;

class ElementContext
{
    protected ?Element $top = null;

    protected int $sequence;

    protected array $namedSpaces = [];

    protected array $uniqueNames = [];

    protected Element $removed;

    protected Player $player;

    protected mixed $moves;

    protected bool $trackMovement;

    public function __construct(
        protected GameManager $gameManager,
        protected array $classRegistry = []
    ) {}

    public static function make(GameManager $gameManager, $classRegistry = []): static
    {
        return new static($gameManager, $classRegistry);
    }

    public function inClassRegistry($class): bool
    {
        return in_array($class, $this->classRegistry);
    }

    public function pushClassRegistry($class): static
    {
        $this->classRegistry[] = $class;

        return $this;
    }

    public function clearClassRegistry(): static
    {
        $this->classRegistry = [];

        return $this;
    }

    public function getGameManager(): GameManager
    {
        return $this->gameManager;
    }

    public function setGameManager(GameManager $gameManager): static
    {
        $this->gameManager = $gameManager;

        return $this;
    }

    public function getTop(): Element|null
    {
        return $this->top;
    }

    public function setTop(Element $top): static
    {
        $this->top = $top;

        return $this;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): static
    {
        $this->sequence = $sequence;

        return $this;
    }

    public function getNamedSpaces(mixed $target = null): mixed
    {
        return is_null($target) ? $this->uniqueNames : $this->uniqueNames[$target];
    }

    public function setNamedSpaces(mixed $target, mixed $value = null): static
    {
        if (!is_null($value)) {
            $this->namedSpaces[$target] = $value;
        } else {
            $this->namedSpaces = $target;
        }

        return $this;
    }

    public function getUniqueNames(mixed $target = null): mixed
    {
        return is_null($target) ? $this->uniqueNames : $this->uniqueNames[$target];
    }

    public function setUniqueNames(mixed $target, mixed $value = null): static
    {
        if (!is_null($value)) {
            $this->uniqueNames[$target] = $value;
        } else {
            $this->uniqueNames = $target;
        }

        return $this;
    }

    public function getRemoved(): Element
    {
        return $this->removed;
    }

    public function setRemoved(Element $removed): static
    {
        $this->removed = $removed;

        return $this;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): static
    {
        $this->player = $player;

        return $this;
    }

    public function getMoves(): mixed
    {
        return $this->moves;
    }

    public function setMoves(mixed $moves): static
    {
        $this->moves = $moves;

        return $this;
    }

    public function getTrackMovement(): bool
    {
        return $this->trackMovement;
    }

    public function setTrackMovement(bool $trackMovement): static
    {
        $this->trackMovement = $trackMovement;

        return $this;
    }
}
