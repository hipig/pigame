<?php

namespace Packages\BoardGame\Game\Player;

use Packages\BoardGame\Game\Board\BoardGame;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Arr;
use \Illuminate\Support\Collection as BaseCollection;

class PlayerCollection extends BaseCollection
{
    /**
     * 当前位置列表
     *
     * @var array[int]
     */
    protected array $currentPosition;

    /**
     * 玩家所属游戏
     *
     * @var BoardGame|null
     */
    protected BoardGame|null $game = null;

    /**
     * 添加玩家
     *
     * @param mixed $player
     * @return $this
     */
    public function addPlayer(mixed $player): static
    {
        if (is_array($player)) {
            $player = Player::make($player);
        }

        $player->setPlayers($this);

        $this->push($player);

        if (!is_null($this->game)) {
            $player->setGame($this->game);
        }

        return $this;
    }

    /**
     * 根据玩家位置获取玩家
     *
     * @param int $position
     * @return Player|null
     */
    public function atPosition(int $position): Player|null
    {
        return $this->first(fn(Player $player) => $player->getPosition() === $position);
    }

    /**
     * 获取当前位置玩家
     *
     * @return Player|PlayerCollection
     */
    public function current(): Player|PlayerCollection
    {
        $players = $this->whereIn('position', $this->currentPosition);

        return $players->count() > 1 ? $players : $players->first();
    }

    /**
     * 获取非当前位置玩家
     *
     * @return PlayerCollection
     */
    public function notCurrent(): PlayerCollection
    {
        return $this->whereNotIn('position', $this->currentPosition);
    }

    /**
     * 设置当前位置玩家
     *
     * @param mixed $items
     * @return $this
     */
    public function setCurrent(mixed $items): static
    {
        $items = is_array($items) ? $items : func_get_args();

        $this->currentPosition = Arr::map($items, fn($item) => $item instanceof Player ? $item->getPosition() : $item);

        return $this;
    }

    /**
     * 根据玩家位置重新排序
     *
     * @return $this
     */
    public function inPositionOrder(): PlayerCollection
    {
        return $this->sortBy('position');
    }

    /**
     * 获取当前位置
     *
     * @return array
     */
    public function getCurrentPosition(): array
    {
        return $this->currentPosition;
    }
}