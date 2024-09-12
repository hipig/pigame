<?php

namespace App\Games\Bzmm;


use App\Games\Bzmm\Card\Card;
use Packages\BoardGame\Contracts\Game as GameContract;
use Packages\BoardGame\Action\Action;
use Packages\BoardGame\Action\Selection;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Space;
use Packages\BoardGame\Flow\ActionStep;
use Packages\BoardGame\Flow\EachPlayerr;
use Packages\BoardGame\Flow\Loop;
use Packages\BoardGame\Player\Player;

class Game implements GameContract
{
    /**
     * 玩家类定义
     *
     * @return string
     */
    public function player(): string
    {
        return Player::class;
    }

    /**
     * 游戏类定义
     *
     * @return string
     */
    public function game(): string
    {
        return BoardGame::class;
    }

    /**
     * 游戏数据定义
     *
     * @param BoardGame $game
     * @return void
     */
    public function board(BoardGame $game): void
    {
        $pool = $game->create(Space::class, 'pool');

        $pool->create(Space::class, 'deal');
        $pool->create(Space::class, 'discard');

        foreach ($game->players as $player) {
            $hand = $game->create(Space::class, 'hand')->setPlayer($player);
            $hand->onEnter(Card::class, fn($card) => $card->showToAll());
        }
    }

    /**
     * 游戏动作定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function actions(BoardGame $game): array
    {
        return [
            fn(Player $player) => Action::make('take')->prompt('Choose a token')->selection(
                Selection\Board::make('token')->choices($game->pool->all(Card::class))
            )->move(
                'token',
                $player->my('hand'),
            )->message(
                `{{player}} drew a {{token}} token.`
            )->do(function ($token) use ($game, $player) {
                if ($token->color === 'red') {
                    $game->message("{{player}} wins!", compact('player'));
                    $game->finish($player);
                }
            })
        ];
    }

    /**
     * 游戏流程定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function flow(BoardGame $game): array
    {
        return [
            fn() => $game->pool->shuffle(), //洗牌
            Loop::make()->do(
                EachPlayerr::make(name: 'player')->do(
                    ActionStep::make(actions: ['take']),
                )
            )
        ];
    }

    /**
     * 游戏子流程定义
     *
     * @param BoardGame $game
     * @return array
     */
    public function subFlow(BoardGame $game): array
    {
        return [
            'take' => [

            ],
        ];
    }
}
