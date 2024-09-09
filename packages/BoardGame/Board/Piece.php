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

    public function putInto(Element $to): static
    {
        // TODO: Implement putInto() method.

        return $this;
    }
}
