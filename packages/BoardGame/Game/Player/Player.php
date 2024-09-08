<?php

namespace Packages\BoardGame\Game\Player;

use ArrayAccess;
use Packages\BoardGame\Game\Board\BoardGame;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use JsonSerializable;
use Stringable;

abstract class Player implements Arrayable, ArrayAccess, Jsonable, JsonSerializable, Stringable
{
    /**
     * 玩家唯一标识
     */
    protected string|int $id;

    /**
     * 玩家名称
     */
    protected string $name;

    /**
     * 玩家颜色标识
     */
    protected string $color;

    /**
     * 玩家头像
     */
    protected string $avatar;

    /**
     * 玩家位置
     */
    protected int $position;

    /**
     * 玩家设置
     */
    protected mixed $settings;

    /**
     * 玩家所属游戏
     */
    protected BoardGame $game;

    /**
     * 玩家列表
     */
    protected PlayerCollection $_players;

    protected array $fillableAttributes = [
        'id',
        'name',
        'color',
        'avatar'
    ];

    /**
     * Player constructor.
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->fill($attributes);
    }

    public static function make(array $attributes = []): static
    {
        return new static($attributes);
    }

    public function isCurrent(): bool
    {
        return in_array($this->position, $this->_players->getCurrentPosition());
    }

    public function setCurrent(): static
    {
        $this->_players->setCurrent($this);
        return $this;
    }

    public function others(): PlayerCollection
    {
        return $this->_players->where('position', '<>', $this->getPosition());
    }


    public function fill(array $attributes): static
    {
        foreach ($attributes as $key => $value) {
            if ($this->isFillable($key)) {
                $this->setAttribute($key, $value);
            }
        }
        return $this;
    }

    public function setAttribute(string $key, mixed $value): static
    {
        $this->$key = $value;
        return $this;
    }

    protected function isFillable(string $key): bool
    {
        return in_array($key, $this->fillableAttributes);
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function isEmpty(): bool
    {
        return empty($this->id);
    }

    public function getPosition(): int
    {
        return $this->position;
    }

    public function setPlayers(PlayerCollection $players): static
    {
        $this->_players = $players;
        return $this;
    }
}