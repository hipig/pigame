<?php

namespace Packages\BoardGame\Action;

use Illuminate\Support\Str;

class Selection
{
    const TYPE_NUMBER = 'NUMBER';
    const TYPE_TEXT = 'TEXT';
    const TYPE_CHOICES = 'CHOICES';
    const TYPE_BOARD = 'BOARD';
    const TYPE_PLACE = 'PLACE';
    const TYPE_BUTTON = 'BUTTON';
    const TYPE_GROUP = 'GROUP';

    const SKIP_IF_ONLY_ONE = 'ONLY_ONE';


    protected string $type;

    protected array $choices = [];

    protected ?int $min = null;

    protected ?int $max = null;

    protected ?string $skipIf = self::SKIP_IF_ONLY_ONE;

    public function __construct(
        protected ?string $name = null,
        protected ?string $prompt = null,
        protected ?string $confirm = null
    ) {}

    public function input(string $name): static
    {
        $this->selection($name, self::TYPE_TEXT);

        return $this;
    }

    public function number(string $name): static
    {
        $this->selection($name, self::TYPE_NUMBER);

        return $this;
    }

    public function choose(string $name, array $choices): static
    {
        $this->choices = $choices;

        $this->selection($name, self::TYPE_CHOICES);

        return $this;
    }

    public function board(string $name, mixed $choices): static
    {
        $this->choices = $choices;

        $this->selection($name, self::TYPE_BOARD);

        return $this;
    }

    public function place(string $name, mixed $piece, $into): static
    {
        $this->selection($name, self::TYPE_PLACE);

        return $this;
    }

    public function group(string $name): static
    {
        $this->selection($name, self::TYPE_GROUP);

        return $this;
    }

    protected function selection($name, $type): static
    {
        $this->name = $name;

        $this->type = $type;

        return $this;
    }

    public function min(int $min): static
    {
        $this->min = $min;

        return $this;
    }

    public function max(int $max): static
    {
        $this->max = $max;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }
}
