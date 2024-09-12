<?php

namespace Packages\BoardGame\Action\Selection;

use Packages\BoardGame\Action\Selection;

class Board extends Selection
{

    protected string $type = self::TYPE_BOARD;

    protected array $choices = [];

    public function choices(array $choices): static
    {
        $this->choices = $choices;

        return $this;
    }
}