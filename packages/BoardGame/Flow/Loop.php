<?php

namespace Packages\BoardGame\Flow;

class Loop extends WhileLoop
{
    public function __construct(...$args)
    {
        parent::__construct(do: $args['do'], while: fn() => true);
    }
}