<?php

namespace Packages\BoardGame\Action\Message;

use Packages\BoardGame\Player\Player;

class Message
{
    protected ?int $position = null;

    public function __construct(
        protected string $text,
        protected ?\Closure $callback = null,
        mixed $position = null
    ) {
        $this->position = $position instanceof Player ? $position->getPosition() : $position;
    }

    public static function make(string $text, ?\Closure $callback = null, mixed $position = null): static
    {
        return new static($text, $callback, $position);
    }
}