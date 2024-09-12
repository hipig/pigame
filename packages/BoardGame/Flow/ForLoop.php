<?php

namespace Packages\BoardGame\Flow;

class ForLoop extends WhileLoop
{
    public function __construct(...$args)
    {
        parent::__construct(do: $args['do'], while: fn() => true);
        $this->name = $args['name'];
        $this->initial = $args['initial'];
        $this->next = $args['next'];
        $this->whileCondition = fn($position) => $args['whileCondition']($position['value']);
    }
}