<?php

namespace Packages\BoardGame\Flow;

use Packages\BoardGame\GameManager;

class Flow
{
    const TYPE_MAIN = 'MAIN';
    const TYPE_ACTION = 'ACTION';
    const TYPE_PARALLEL = 'PARALLEL';
    const TYPE_LOOP = 'LOOP';
    const TYPE_FOREACH = 'FOREACH';
    const TYPE_SWITCH_CASE = 'SWITCH_CASE';

    protected string $name;
    protected int $position;
    protected int $sequence = 0;
    protected string $type = self::TYPE_MAIN;
    protected Flow|\Closure|null $step = null;
    public array|Flow|\Closure|null $block = null;
    public ?array $args = null;
    public ?Flow $top = null;
    public ?Flow $parent = null;
    public ?GameManager $gameManager = null;
}
