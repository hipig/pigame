<?php

namespace Packages\BoardGame\Game\Board\Element;

use Packages\BoardGame\Game\GameManager;
use Packages\BoardGame\Game\Player\Player;

abstract class ElementContext
{
    protected GameManager $gameManager;

    protected Element|null $top;

    protected int $sequence;

    protected array $classRegistry;

    protected mixed $namedSpaces;

    protected mixed $uniqueNames;

    protected Element $removed;

    protected Player $player;

    protected mixed $moves;

    protected bool $trackMovement;

    public function inClassRegistry($class): bool
    {
        return in_array($class, $this->classRegistry);
    }

    public function pushClassRegistry($class): void
    {
        $this->classRegistry[] = $class;
    }

    public function clearClassRegistry(): void
    {
        $this->classRegistry = [];
    }

    public function getGameManager(): GameManager
    {
        return $this->gameManager;
    }

    public function setGameManager(GameManager $gameManager): void
    {
        $this->gameManager = $gameManager;
    }

    public function getTop(): Element|null
    {
        return $this->top;
    }

    public function setTop(Element $top): void
    {
        $this->top = $top;
    }

    public function getSequence(): int
    {
        return $this->sequence;
    }

    public function setSequence(int $sequence): void
    {
        $this->sequence = $sequence;
    }

    public function getNamedSpaces(mixed $target = null): mixed
    {
        return is_null($target) ? $this->uniqueNames : $this->uniqueNames[$target];
    }

    public function setNamedSpaces(mixed $target, mixed $value = null): void
    {
        if (!is_null($value)) {
            $this->namedSpaces[$target] = $value;
        } else {
            $this->namedSpaces = $target;
        }
    }

    public function getUniqueNames(mixed $target = null): mixed
    {
        return  is_null($target) ? $this->uniqueNames : $this->uniqueNames[$target];
    }

    public function setUniqueNames(mixed $target, mixed $value = null): void
    {
        if (!is_null($value)) {
            $this->uniqueNames[$target] = $value;
        } else {
            $this->uniqueNames = $target;
        }
    }

    public function getRemoved(): Element
    {
        return $this->removed;
    }

    public function setRemoved(Element $removed): void
    {
        $this->removed = $removed;
    }

    public function getPlayer(): Player
    {
        return $this->player;
    }

    public function setPlayer(Player $player): void
    {
        $this->player = $player;
    }

    public function getMoves(): mixed
    {
        return $this->moves;
    }

    public function setMoves(mixed $moves): void
    {
        $this->moves = $moves;
    }

    public function getTrackMovement(): bool
    {
        return $this->trackMovement;
    }

    public function setTrackMovement(bool $trackMovement): void
    {
        $this->trackMovement = $trackMovement;
    }
}