<?php

namespace Packages\BoardGame\Board\Element;

abstract class ElementTree
{
    const ORDER_NORMAL = 'NORMAL';
    const ORDER_STACKING = 'STACKING';

    protected function __construct(
        protected int               $id,
        protected int               $ref,
        protected ElementCollection $children,
        protected ?\Closure         $setId = null,
        protected ?Element          $parent = null,
        protected ?string           $order = null,
        protected ?int              $wasRef = null,
        protected ?bool             $moved = null,
    )   {}

    public static function make(int $id, int $ref, ElementCollection $children, ?\Closure $setId = null): static
    {
        return new static($id, $ref, $children, $setId);
    }

    public function setId($id): void
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setRef($ref): void
    {
        $this->ref = $ref;
    }

    public function getRef(): int
    {
        return $this->ref;
    }

    public function setParent($parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): Element|null
    {
        return $this->parent;
    }

    public function setChildren($children): void
    {
        $this->children = $children;
    }

    public function getChildren(): ElementCollection
    {
        return $this->children;
    }

    public function setOrder($order): void
    {
        if (!in_array($order, [self::ORDER_NORMAL, self::ORDER_STACKING])) {
            throw new \InvalidArgumentException();
        }

        $this->order = $order;
    }

    public function getOrder(): string
    {
        return $this->order;
    }

    public function setWasRef($wasRef): void
    {
        $this->wasRef = $wasRef;
    }

    public function getWasRef(): int
    {
        return $this->wasRef;
    }

    public function setMoved($moved): void
    {
        $this->moved = $moved;
    }

    public function getMoved(): bool
    {
        return $this->moved;
    }
}
