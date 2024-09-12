<?php

namespace Packages\BoardGame\Board;

use Illuminate\Support\Arr;
use Packages\BoardGame\Board\Element\Element;

class Piece extends Element
{

    protected ?array $_visible = [
        'default' => false
    ];

    static bool $isPiece = true;

    protected function createElement(string $element, string $name =  null, \Closure|array|null $attributes = null): Element
    {
        if (is_subclass_of($element, Space::class) || $element === Space::class) {
            throw new \Exception('不能在 Piece 中创建 Space 元素');
        }

        return parent::createElement($element, $name, $attributes);
    }

    public function showToAll(): static
    {
        unset($this->_visible);

        return $this;
    }

    public function hideFromAll(): static
    {
        $this->_visible = [
            'default' => false
        ];

        return $this;
    }

    public function showToOnly(mixed $player): static
    {
        $player = is_int($player) ? $player : $player->getPosition();
        $this->_visible = [
            'default' => false,
            'except' => [$player]
        ];

        return $this;
    }

    public function showTo(mixed $players)
    {
        $players = is_array($players) ? $players : func_get_args();
        $players = Arr::map($players, fn($player) => is_int($player) ? $player : $player->getPosition());

        if (is_null($this->_visible)) return;

        if ($this->_visible['default'] ?? false) {
            if (!($this->_visible['except'] ?? false)) return;
            $this->_visible['except'] = array_diff($this->_visible['except'], $players);
        } else {
            $this->_visible['except'] = array_merge($players, $this->_visible['except'] ?? []);
        }

        return $this;
    }

    public function setVisible($visible): static
    {
        $this->_visible = $visible;

        return $this;
    }

    public function position()
    {
        return $this->_t->getParent()?->_t?->getChildren()->search(fn($e) => $e === $this) ?? -1;
    }

    function putInto(Element $to, $options = []): static
    {
        if ($to->isDescendantOf($this)) {
            throw new \RuntimeException("不能移动 " . $this . " 元素自己");
        }

        $pos = $to->_t->getOrder() === 'stacking' ? 0 : count($to->_t->getChildren());
        if (isset($options['position'])) {
            $pos = $options['position'] >= 0 ? $options['position'] : count($to->_t->getChildren()) + $options['position'] + 1;
        }
        if (isset($options['fromTop'])) {
            $pos = $options['fromTop'];
        }
        if (isset($options['fromBottom'])) {
            $pos = count($to->_t->getChildren()) - $options['fromBottom'];
        }

        $previousParent = $this->_t->getParent();
        $position = $this->position();

        $refs = ($previousParent === $to && !isset($options['row']) && !isset($options['column'])) ? $to->childRefsIfObscured() : [];
        $this->_t->getParent()->_t->getChildren()->splice($position, 1);
        $this->_t->setParent($to);
        $to->_t->getChildren()->splice($pos, 0, [$this]);

        if ($refs) {
            $to->assignChildRefs($refs);
        }

        if ($previousParent !== $to && $previousParent instanceof Space) {
            $previousParent->triggerEvent("exit", $this);
        }
        if ($previousParent !== $to && $this->_ctx->getTrackMovement()) {
            $this->_t->setMoved(true);
        }

        unset($this->column);
        unset($this->row);
        if (isset($options['row'])) {
            $this->row = $options['row'];
        }
        if (isset($options['column'])) {
            $this->column = $options['column'];
        }

        if ($previousParent !== $to && $to instanceof Space) {
            $to->triggerEvent("enter", $this);
        }

        return $this;
    }
}
