<?php

namespace App\Game;

abstract class Game
{
    /**
     * Game name
     * @var string
     */
    protected string $name;

    protected array $moves = [];

    protected Turn $turn;

    protected function setup(): static
    {
        return $this;
    }

    protected function move(Move $move): static
    {
        $this->moves[$move->getName()] = $move;
        return $this;
    }

    protected function moves(/* args... */): static
    {
        $args = func_get_args();

        if (isset($args[0]) && is_array($args[0])) {
            $args = $args[0];
        }

        foreach ($args as $arg) {
            $this->move($arg);
        }

        return $this;
    }
}