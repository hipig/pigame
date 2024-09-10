<?php

namespace App\Games\Bzmm;


use App\Games\Bzmm\Card\Card;
use Packages\BoardGame\Action\Action;
use Packages\BoardGame\Action\Selection;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Space;
use Packages\BoardGame\Player\Player;

class Game extends \Packages\BoardGame\GameCreator
{
    public function createGame(BoardGame $game): void
    {
        $pool = $game->create(Space::class, 'pool');

        $pool->create(Space::class, 'deal');
        $pool->create(Space::class, 'discard');

        foreach ($game->players as $player) {
            $hand = $game->create(Space::class, 'hand')->setPlayer($player);
            $hand->onEnter(Card::class, fn($card) => $card->showToAll());
        }


        $game->defineActions(
            fn(Player $player) => Action::make('take')->defineSelections(
                Selection\Number::make('test1'),
                Selection\Number::make('test2')->min(1)->max(2),
            ),
            fn(Player $player) => Action::make('take2')->defineSelections(
                Selection\Number::make('test1'),
                Selection\Number::make('test2')->min(1)->max(2),
            ),
        );

        dd($game);
    }
}
