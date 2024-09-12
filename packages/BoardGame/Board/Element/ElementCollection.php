<?php

namespace Packages\BoardGame\Board\Element;

use Illuminate\Support\Collection;

class ElementCollection extends Collection
{

    public function putInto(Element $to, array $options = null): static
    {
        foreach ($this->items as $item) {
            if (property_exists($item, 'isSpace')) throw new \RuntimeException('不能移动 Space');
            $item->putInto($to, $options);
        }

        return $this;
    }
}
