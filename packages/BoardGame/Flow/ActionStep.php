<?php

namespace Packages\BoardGame\Flow;

class ActionStep extends Flow
{
    const SKIP_IF_ALWAYS = 'ALWAYS';
    const SKIP_IF_NEVER = 'NEVER';
    const SKIP_IF_ONLY_ONE = 'ONLY_ONE';

    protected string $type = self::TYPE_ACTION;

    protected array $players = [];

    protected array $actions = [];

    protected ?string $prompt = null;

    protected ?string $description = null;

    protected ?\Closure $condition = null;

    protected bool $continueIfImpossible = false;

    protected \Closure|string|null $repeatUntil = null;

    protected \Closure|string|null $optional = null;

    protected ?string $skipIf = null;

    public function __construct(...$args)
    {
        parent::__construct(name: $args['name']);
        $this->players = $args['players'] ?? [];
        $this->actions = array_map(fn($action) => is_string($action) ? ['name' => $action] : $action, $args['actions'] ?? []);
        $this->prompt = $args['prompt'] ?? null;
        $this->description = $args['description'] ?? null;
        $this->condition = $args['condition'] ?? null;
        $this->continueIfImpossible = $args['continueIfImpossible'] ?? false;
        if ($args['repeatUntil'] ?? false) {
            $this->repeatUntil = true;
            $this->actions[] = ['name' => '__PASS__', 'prompt' => is_string($args['repeatUntil']) ? $args['repeatUntil'] : $args['repeatUntil']($this->flowStepArgs())];
        } else if ($args['optional']?? false) {
            $this->actions[] = ['name' => '__PASS__', 'prompt' => is_string($args['optional']) ? $args['optional'] : $args['optional']($this->flowStepArgs())];
        }
        $this->skipIf = $args['skipIf'] ?? self::SKIP_IF_ALWAYS;
    }


}
