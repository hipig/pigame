<?php

namespace Packages\BoardGame\Board;

use Packages\BoardGame\Board\Element\Element;

class Space extends Element
{
    const EVENT_ENTER = 'ENTER';

    const EVENT_EXIT = 'EXIT';

    protected array $_eventHandlers = [];

    protected ?array $_visOnEnter = null;

    protected array|string $_screen = 'owner';

    static bool $isSpace = true;

    public function create(string $element, string $name = null, ?array $attributes = null): Element
    {
        $el = parent::create($element, $name, $attributes);
        if (method_exists($el, 'showTo')) {
            $this->triggerEvent(self::EVENT_ENTER, $el);
        }
        return $el;
    }

    protected function addEventHandler(string $event, $type, $handler): void
    {
        $this->throwPhaseError('无法响应事件。');

        $this->_eventHandlers[$event][$type][] = $handler;
    }

    public function onEnter($type, $handler): void
    {
        $this->addEventHandler(self::EVENT_ENTER, $type, $handler);
    }

    public function onExit($type, $handler): void
    {
        $this->addEventHandler(self::EVENT_EXIT, $type, $handler);
    }

    public function triggerEvent(string $event, Element $el): void
    {
        if ($this->_visOnEnter) {
            $el->setVisible([
                'default' => $this->_visOnEnter['default'],
                'except' => $this->_visOnEnter['except'] === 'owner' ? ($this->owner ? $this->owner->getPosition() : null) : $this->_visOnEnter['except']
            ]);
        };

        if (!isset($this->_eventHandlers[$event])) return;

        foreach ($this->_eventHandlers[$event] as $type => $handlers) {
            if (!($el instanceof $type)) continue;

            foreach ($handlers as $handler) {
                $handler($el);
            }
        }
    }
}
