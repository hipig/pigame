<?php

namespace Core\Turn;
class Turn
{
    private function __construct(
        private int $turnNumber,
        private string $currentPlayer = '',
        private array $playOrder = [],
        private string $phase = '',
        private array $activePlayers = []
    ) { }

    public static function init()
    {
        return new Turn(
            turnNumber: 1
        );
    }

    public function setPlayOrder(array $order)
    {
        $this->playOrder = $order;
        $this->currentPlayer = $order[0];
    }
}