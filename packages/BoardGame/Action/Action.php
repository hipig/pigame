<?php

namespace Packages\BoardGame\Action;

use Illuminate\Support\Str;

class Action
{
    protected \Closure|bool $condition = true;
    protected array $selections = [];

    protected array $moves = [];

    protected array $messages = [];

    protected array $order = [];

    protected bool $mutated = false;

    public function __construct(
        protected ?string $name = null,
        protected ?string $prompt = null,
        protected ?string $description = null
    )
    {
        if (is_null($this->name)) {
            $this->name = Str::lower(class_basename($this));
        }
    }

    public function name($name): static
    {
        $this->name = $name;

        return $this;
    }

    public function prompt($prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function description($description): static
    {
        $this->description = $description;

        return $this;
    }

    public function selection(\Closure $callback): static
    {
        tap($this->createSelection(), function (Selection $selection) use ($callback) {
            $callback($selection);

            $this->addSelection($selection);
        });

        return $this;
    }

    protected function addSelection(Selection $selection): static
    {
        if (is_null($selection->getName())) {
            throw new \RuntimeException('选择器名称不能为空');
        }

        if (in_array($selection->getName(), array_column($this->selections, 'name'))) {
            throw new \RuntimeException('选择器名称不能重复');
        }

        if ($this->mutated) {
            throw new \RuntimeException('选择器应该在执行动作之前添加');
        }

        $this->selections[] = $selection;

        return $this;
    }

    protected function createSelection(): Selection
    {
        return new Selection();
    }
}
