<?php

namespace Packages\BoardGame\Action\Selection;

use Packages\BoardGame\Action\Selection;

class Choose extends Selection
{
    use Selection\Concerns\Minimax;

    protected string $type = self::TYPE_CHOOSE;

}