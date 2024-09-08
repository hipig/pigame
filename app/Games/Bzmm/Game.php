<?php

namespace App\Games\Bzmm;


use App\Games\Bzmm\Card\Card;
use Packages\BoardGame\Game\Action\Action;
use Packages\BoardGame\Game\Board\Piece;
use Packages\BoardGame\Game\Board\Space;
use Packages\BoardGame\Game\Player\Player;
use \Packages\BoardGame\Game\Board\BoardGame;

class Game
{
    public function setup(BoardGame $game)
    {
        $pool = $game->create(Space::class, 'pool');

        $deal = $pool->create(Space::class, 'deal');
        $discard = $pool->create(Space::class, 'discard');

        foreach ($game->players as $player) {
            $hand = $game->create(Space::class, 'hand')->setPlayer($player);
            $hand->onEnter(Card::class, fn($card) => $card->showToAll());
        }

        $hand = $game->create(Space::class, 'hand');
        $hand->create(Piece::class, '');

        $game->defineActions(function (Action $action) {
            $action->name('take')->handler(function (Player $player) {

            });
        });
    }
}