<?php

namespace Traverse;

interface Reasoning
{
    /**
     * Decide the next action to take based on the goal, the current state and the history of previous actions.
     * @param array<Action> $history
     * @throws NoActionFoundException
     */
    public function decideNext(string $goal, ?string $snapshot, array $history): ?Action;
}
