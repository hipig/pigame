<?php

namespace App\Game;

interface Phase
{
    public function onBegin();

    public function onEnd();

    public function endIf(): bool;

    public function moves(): array;

    public function turn();
}