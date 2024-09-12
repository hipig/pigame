<?php

namespace Packages\BoardGame\Action;

abstract class Selection
{
    const TYPE_NUMBER = 'NUMBER';
    const TYPE_INPUT = 'INPUT';
    const TYPE_CHOOSE = 'CHOOSE';
    const TYPE_BOARD = 'BOARD';
    const TYPE_PLACE = 'PLACE';
    const TYPE_BUTTON = 'BUTTON';
    const TYPE_GROUP = 'GROUP';

    const SKIP_IF_ONLY_ONE = 'ONLY_ONE';


    protected string $type;

    protected array $clientContext = [];

    protected array $choices = [];

    protected ?string $skipIf = self::SKIP_IF_ONLY_ONE;

    public function __construct(
        protected string $name,
        protected ?string $prompt = null,
        protected ?string $confirm = null
    ) {}

    public static function make($name): static
    {
        return new static($name);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function prompt(mixed $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function confirm(mixed $confirm): static
    {
        $this->confirm = $confirm;

        return $this;
    }

    public function setClientContext($clientContext): static
    {
        $this->clientContext = $clientContext;

        return $this;
    }

    public function isMulti(): bool
    {
        return in_array($this->type, [self::TYPE_CHOOSE, self::TYPE_BOARD]) && !is_null($this->min) && !is_null($this->max);
    }

    public function isBoardChoose(): bool
    {
        return in_array($this->type, [self::TYPE_BOARD, self::TYPE_PLACE]);
    }
}
