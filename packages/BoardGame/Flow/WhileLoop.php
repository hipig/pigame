<?php

namespace Packages\BoardGame\Flow;

class WhileLoop extends Flow
{
    protected string $type = self::TYPE_LOOP;

    protected array $position = [];
    protected ?\Closure $initial = null;

    protected ?\Closure $next = null;
    protected \Closure $whileCondition;
    public function __construct(...$args)
    {
        parent::__construct(do: $args['do']);
        $this->whileCondition = fn() => $args['while']($this->flowStepArgs());
    }

    public function reset(): static
    {
        $position = ['index' => 0];
        if ($this->initial !== null) {
            $position['value'] = is_callable($this->initial) ? call_user_func($this->initial, $this->flowStepArgs()) : $this->initial;
        }

        if (!$this->whileCondition->__invoke($position)) {
            $this->setPosition(array_merge($position, ['index' => -1]));
        } else {
            $this->setPosition($position);
        }

        return $this;
    }

    public function currentBlock()
    {
        if ($this->position['index'] !== -1) return $this->block;
    }
}