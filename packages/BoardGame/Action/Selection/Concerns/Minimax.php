<?php

namespace Packages\BoardGame\Action\Selection\Concerns;

trait Minimax
{
    protected ?int $min = null;

    protected ?int $max = null;

    public function min(int $min): self
    {
        $this->min = $min;
        return $this;
    }

    public function max(int $max): self
    {
        $this->max = $max;
        return $this;
    }
}