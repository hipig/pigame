<?php

namespace Packages\BoardGame\Concerns;

use Packages\BoardGame\GameManager;

trait WithPhaseError
{
    public function throwPhaseError($message, string $currentPhase, string $phase = GameManager::PHASE_STARTED): void
    {
        $phasePrefix = match ($phase) {
            GameManager::PHASE_STARTED => '游戏已开始',
            default => ''
        };
        if ($currentPhase === $phase) {
            throw new \RuntimeException($phasePrefix ? $phasePrefix . '：' . $message : $message);
        }
    }
}