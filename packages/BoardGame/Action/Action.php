<?php

namespace Packages\BoardGame\Action;

use Illuminate\Support\Str;
use Packages\BoardGame\Action\Message\Message;
use Packages\BoardGame\Action\Selection\Button;
use Packages\BoardGame\Board\BoardGame;
use Packages\BoardGame\Board\Element\Element;
use Packages\BoardGame\Board\Element\ElementCollection;
use Packages\BoardGame\Board\Piece;

class Action
{
    const ORDER_MOVE = 'MOVE';
    const ORDER_MESSAGE = 'MESSAGE';

    protected \Closure|bool $condition = true;
    protected array $selections = [];

    protected array $moves = [];

    protected array $messages = [];

    protected array $order = [];

    protected bool $mutated = false;

    protected ?BoardGame $game = null;

    public function __construct(
        protected string $name,
        protected ?string $prompt = null,
        protected ?string $description = null
    )
    {
        if (is_null($this->name)) {
            $this->name = Str::lower(class_basename($this));
        }
    }

    public static function make($name): static
    {
        return new static($name);
    }

    public function prompt($prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function description($description): static
    {
        $this->description = $description;

        return $this;
    }

    public function defineSelections(mixed ...$selections): static
    {
        foreach ($selections as $selection) {
            $this->addSelection($selection);
        }

        return $this;
    }

    protected function addSelection(Selection $selection): static
    {
        if (is_null($selection->getName())) {
            throw new \RuntimeException('选择器名称不能为空');
        }

        if (in_array($selection->getName(), array_column($this->selections, 'name'))) {
            throw new \RuntimeException('选择器名称不能重复');
        }

        if ($this->mutated) {
            throw new \RuntimeException('选择器应该在执行动作之前添加');
        }

        $this->selections[] = $selection;

        return $this;
    }

    public function do(\Closure $move): static
    {
        $this->mutated = true;

        $this->moves[] = $move;

        $this->order[] = self::ORDER_MOVE;

        return $this;
    }

    public function message(string $text, \Closure $args = null): static
    {
        return $this->messageTo(null, $text, $args);
    }

    public function messageTo(mixed $position, string $text, \Closure $args = null): static
    {
        $this->messages[] = Message::make($text, $args, $position);

        $this->order[] = self::ORDER_MESSAGE;

        return $this;
    }

    public function confirm(mixed $prompt): static
    {
        $confirm = is_string($prompt) ? $prompt : Message::make('{{__message__}}', function () use ($prompt) {
            return [
                '__message__' => $prompt()
            ];
        });
        return $this->addSelection(Button::make('__confirm__')->prompt($prompt)->confirm($confirm));
    }

    public function move(Piece|string $piece, Element|string $into): static
    {
        $this->do(function ($args) use ($piece, $into) {
            $selectedPiece = $piece instanceof Piece ? $piece : $args[$piece];
            $selectedInto = $into instanceof Element ? $into : $args[$into];
            if (is_array($selectedPiece)) {
                (new ElementCollection(...$selectedPiece))->putInto($selectedInto);
            } else {
                $selectedPiece->putInto($selectedInto);
            }
        });

        $pieceSelection = is_string($piece) ? $this->selections[$piece] : null;
        $intoSelection = is_string($into) ? $this->selections[$into] : null;

        if ($intoSelection && $intoSelection['type'] !== Selection::TYPE_BOARD) {
            throw new \RuntimeException("Invalid move: \"$into\" must be the name of a previous chooseOnBoard");
        }

        if ($pieceSelection && $pieceSelection['type'] !== Selection::TYPE_BOARD) {
            throw new \RuntimeException("Invalid move: \"$piece\" must be the name of a previous chooseOnBoard");
        }

        if ($intoSelection && $intoSelection->isMulti()) {
            throw new \RuntimeException("Invalid move: May not move into a multiple choice selection");
        }

        if ($pieceSelection && !$pieceSelection->isMulti()) {
            $pieceSelection->setClientContext(['dragInto' => $intoSelection ?? $into]);
        }

        if ($intoSelection) {
            $intoSelection->setClientContext(['dragFrom' => $pieceSelection ?? $piece]);
        }

        return $this;
    }
}
