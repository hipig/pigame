<?php

namespace App\Game;

abstract class Move
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }
}