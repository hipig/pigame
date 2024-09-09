<?php

namespace App\Games\Bzmm;


use App\Games\Bzmm\Card\Card;
use Packages\BoardGame\Action\Action;
use Packages\BoardGame\Action\Selection;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Space;

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

        $game->defineActions(function (Action $action) {
            $action->name('take')->selection(function (Selection $selection) {
                $selection->number('number1')->min(1);
                $selection->input('text1');
            });
            $action->name('take2')->selection(function (Selection $selection) {
                $selection->number('test1');
            });
        });
    }
}
