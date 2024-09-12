<?php

namespace Packages\BoardGame\Flow;

use Packages\BoardGame\GameManager;

abstract class Flow
{
    const TYPE_MAIN = 'MAIN';
    const TYPE_ACTION = 'ACTION';
    const TYPE_PARALLEL = 'PARALLEL';
    const TYPE_LOOP = 'LOOP';
    const TYPE_FOREACH = 'FOREACH';
    const TYPE_SWITCH_CASE = 'SWITCH_CASE';

    protected string $name;
    protected array $position = [];
    protected int $sequence = 0;
    protected string $type = self::TYPE_MAIN;
    protected Flow|\Closure|null $step = null;
    public array|Flow|\Closure|null $block = null;
    public ?array $args = null;
    public ?Flow $top = null;
    public ?Flow $parent = null;
    public ?GameManager $gameManager = null;

    public function __construct(...$args)
    {
        $this->name = $args['name'] ?? null;
        $this->block = $args['do'] ?? null;
        $this->top = $this;
    }

    public static function make(...$args): static
    {
        return new static(...$args);
    }

    public function do(mixed ...$do): static
    {
        $this->block = $do;

        return $this;
    }

    public function flowStepArgs(): array
    {
        $args = [...($this->top->args ?? [])];
        $flow = $this->top;
        while ($flow instanceof Flow) {
            $args = [...$flow->flowStepArgs(), ...$args];
            $flow = $flow->step;
        }

        return $args;
    }

    public function currentBlock()
    {
        return $this->block;
    }


    public function currentProcessor(): static
    {
        if ($this->step instanceof Flow) {
            return $this->step->currentProcessor();
        }
        if (in_array($this->type, [self::TYPE_ACTION, self::TYPE_PARALLEL])) {
            return $this;
        }

        throw new \RuntimeException('未知流程');
    }

    function reset(): static
    {
        $this->setPosition(null);

        return $this;
    }

    public function setPosition($position, $sequence = null, $reset = true): static
    {
        $this->position = $position;
        $block = $this->currentBlock();
        if (!$block) {
            $this->step = null; // awaiting action or unreachable step
        } else if (is_array($block)) {
            if ($sequence === null) $sequence = 0;
            $this->sequence = $sequence;
            if (!isset($block[$sequence])) throw new \RuntimeException("Invalid sequence for {$this->type}:{$this->name} {$sequence}/" . count($block));
            $this->step = $block[$sequence];
        } else {
            $this->step = $block;
        }

        if ($this->step instanceof Flow) {
            $this->step->gameManager = $this->gameManager;
            $this->step->top = $this->top;
            $this->step->parent = $this;
            if ($reset) $this->step->reset();
        }

        return $this;
    }
}
