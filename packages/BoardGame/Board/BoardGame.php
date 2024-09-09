<?php

namespace Packages\BoardGame\Board;

use Packages\BoardGame\Action\Action;

class BoardGame extends Space
{

    public function defineActions(\Closure $callback): static
    {
        tap($this->createAction(), function (Action $action) use ($callback) {
            $callback($action);

            $this->_ctx->getGameManager()->addAction($action);
        });

        return $this;
    }

    protected function createAction(): Action
    {
        return new Action();
    }
}
