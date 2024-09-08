<?php

namespace Packages\BoardGame\Game;


class GameManager
{
    const PHASE_STARTED  = 'STARTED';


    protected string $phase;


    public function getPhase(): string
    {
        return $this->phase;
    }
}