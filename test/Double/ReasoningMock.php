<?php

namespace Traverse\Test\Double;

use Traverse\Action;
use Traverse\NoActionFoundException;
use Traverse\Reasoning;

final class ReasoningMock implements Reasoning
{
    private bool $noActionFound = false;

    private array $nextActions = [];

    public function decideNext(string $goal, ?string $snapshot, array $history): ?Action
    {
        if ($this->noActionFound) {
            throw new NoActionFoundException();
        }

        return array_shift($this->nextActions);
    }

    public function setNoActionFound(bool $noActionFound): self
    {
        $this->noActionFound = $noActionFound;

        return $this;
    }

    public function addNextAction(Action $action): self
    {
        $this->nextActions[] = $action;

        return $this;
    }
}
