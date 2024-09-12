<?php

namespace Packages\BoardGame\Board;

use Packages\BoardGame\Board\Element\Element;
use Packages\BoardGame\Board\Element\ElementContext;
use Packages\BoardGame\Player\Player;
use Packages\BoardGame\Player\PlayerCollection;

class BoardGame extends Space
{
    protected ?Element $pile = null;

    protected ?PlayerCollection $players = null;

    protected ?Player $player = null;

    protected ?\Closure $random = null;

    public function __construct(ElementContext $ctx)
    {
        parent::__construct((clone $ctx)->setTrackMovement(false));
        $this->game = $this;
        $this->random = function () {
            return mt_rand();
        };
        if ($ctx->getGameManager()) {
            $this->players = $ctx->getGameManager()->getPlayers();
        }
        $this->_ctx->setRemoved($this->createElement(Space::class, 'removed'));
        $this->pile = $this->_ctx->getRemoved();
    }

    public function defineActions(mixed ...$actions): static
    {
        foreach ($actions as $action) {
            $this->_ctx->getGameManager()->addAction($action);
        }

        return $this;
    }

    public function getRandom(): ?\Closure
    {
        return $this->random;
    }

    public function setRandom($random): static
    {
        $this->random = $random;

        return $this;
    }
}
