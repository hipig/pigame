<?php

namespace Packages\BoardGame\Flow;

class Each extends Flow
{
    public function __construct(...$args)
    {
        parent::__construct(
            name: $args['name'],
            initial: fn() => (is_array($args['collection']) ? $args['collection'] : $args['collection']($this->flowStepArgs()))[0],
            next: fn() => $this->position,
            while: fn() => true,
            do: $args['do'],
        );
    }
}