<?php

namespace Packages\BoardGame\Board\Element;

use Illuminate\Support\Str;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Concerns\WithPhaseError;
use Packages\BoardGame\GameManager;
use Packages\BoardGame\Player\Player;

class Element implements \Stringable
{
    use WithPhaseError;

    protected string $name;
    protected ?Player $player = null;

    protected int $row;

    protected int $column;

    protected BoardGame $game;

    protected int $_rotation;

    protected ElementContext $_ctx;

    protected ElementTree $_t;

    static bool $isGameElement = true;

    static array $unserializableAttributes = ['_ctx', '_t', 'game'];

    static array $visibleAttributes = [];

    public function __construct(ElementContext $ctx)
    {
        $ctx->clearClassRegistry();

        if (!$ctx->getTop()) {
            $ctx->setTop($this);
            $ctx->setSequence(0);
        }

        if (!$ctx->getNamedSpaces()) {
            $ctx->setNamedSpaces([]);
            $ctx->setUniqueNames([]);
        }

        $this->_t = ElementTree::make(
            $ctx->getSequence(),
            $ctx->getSequence(),
            new ElementCollection(),
            function ($id) use ($ctx) {
                if ($id) {
                    $this->_t->setId($id);
                    if ($ctx->getSequence() < $id) $ctx->setSequence($id);
                }
            }
        );

        $ctx->setSequence($ctx->getSequence() + 1);

        $this->_ctx = $ctx;
    }

    public function __toString()
    {
        return Str::lower($this->name ?? class_basename($this));
    }

    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if ($name === 'owner') {
            return is_null($this->player) ? $this->_t->getParent()?->player : $this->player;
        }

        return null;
    }

    /**
     * 添加元素
     *
     * @param string $element
     * @param string|null $name
     * @param array|null $attributes
     * @return Element
     */
    public function create(string $element, string $name =  null, array|null $attributes = null): Element
    {
        $this->throwPhaseError('无法创建游戏元素。', $this->_ctx->getGameManager()?->getPhase());

        $el = $this->createElement($element, $name, $attributes);

        $el->_t->setParent($this);

        $firstPiece = $this->_t->getChildren()->search(fn($e) => !property_exists($e, 'isSpace'));
        $iStacking = $this->_t->getOrder() === ElementTree::ORDER_STACKING;
        $isSpace = property_exists($el, 'isSpace');

        if ($iStacking && !$isSpace) {
            $newChildren = $this->_t->getChildren()->unshift($el);
            if ($firstPiece) {
                $newChildren = $this->_t->getChildren()->splice($firstPiece, 0, $el);
            }
        } else {
            $newChildren = $this->_t->getChildren()->push($el);
            if ($isSpace && $firstPiece) {
                $newChildren = $this->_t->getChildren()->splice($firstPiece, 0, $el);
            }
        }

        $this->_t->setChildren($newChildren);

        if ($isSpace) {
            if (in_array($el->name, $this->_ctx->getUniqueNames())) {
                unset($this->_ctx->getNamedSpaces()[$name]);
                $this->_ctx->setUniqueNames($el->name, false);
            } else {
                $this->_ctx->setNamedSpaces($el->name, $el);
                $this->_ctx->setUniqueNames($el->name, true);
            }
        }

        return $el;
    }

    /**
     * 添加多个元素
     *
     * @param int $n
     * @param string $element
     * @param string|null $name
     * @param array|null $attributes
     * @return ElementCollection
     */
    public function createMany(int $n, string $element, string $name =  null, array|null $attributes = null): ElementCollection
    {
        $collection = new ElementCollection();
        for ($i = 0; $i < $n; $i++) {
            $collection->push($this->create($element, $name, $attributes));
        }
        return $collection;
    }

    /**
     * 添加元素
     *
     * @param string $element
     * @param string|null $name
     * @param \Closure|array|null $attributes
     * @return mixed|Element
     */
    protected function createElement(string $element, string $name =  null, \Closure|array|null $attributes = null): mixed
    {
        $elementName = Str::lower(class_basename($element));
        $name = $name ?? $elementName;

        if (!$this->_ctx->inClassRegistry($element)) {
            $this->_ctx->pushClassRegistry($element);
        }

        $el = new $element($this->_ctx);
        $el->setName($name);
        $el->setGame($this->game);

        if ($attributes) {
            if (is_array($attributes)) {
                foreach ($attributes as $key => $value) {
                    $el->$key = $value;
                }
            } else {
                $attributes($el);
            }

            foreach ($attributes as $key => $value) {
                $el->$key = $value;
            }
        }

        if (property_exists($el, 'afterCreation')) {
            call_user_func($el->afterCreation, $this);
        }

        return $el;
    }

    public function setName(string $name): Element
    {
        $this->name = $name;

        return $this;
    }

    public function setPlayer(Player $player): Element
    {
        $this->player = $player;

        return $this;
    }

    public function setGame(BoardGame $game): Element
    {
        $this->game = $game;

        return $this;
    }

    public function childRefsIfObscured(): ?array
    {
        if ($this->_t->getOrder() !== 'stacking') {
            return null;
        }

        $refs = [];
        foreach ($this->_t->getChildren() as $child) {
            if ($this->_ctx->getTrackMovement()) {
                if (!isset($child->_t->wasRef)) {
                    $child->_t->wasRef = $child->_t->ref;
                }
            }
            $refs[] = $child->_t->ref;
        }

        return $refs;
    }

    public function assignChildRefs(array $refs = []): static
    {
        foreach ($refs as $i => $ref) {
            $this->_t->getChildren()[$i]->_t->setRef($ref);
        }

        return $this;
    }

    public function hasMoved(): bool
    {
        return $this->_t->getMoved() ?? !!$this->_t->getParent()?->hasMoved();
    }

    public function resetMovementTracking(): static
    {
        $this->_t->setMoved(false);
        foreach ($this->_t->getChildren() as $child) {
            $child->resetMovementTracking();
        }

        return $this;
    }

    public function resetRefTracking(): static
    {
        $this->_t->setWasRef(null);
        foreach ($this->_t->getChildren() as $child) {
            $child->resetRefTracking();
        }

        return $this;
    }

    public function isDescendantOf(Element $element): bool
    {
        return $this->_t->getParent() === $element || $this->_t->getParent()?->isDescendantOf($element);
    }
}
