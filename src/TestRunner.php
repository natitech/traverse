<?php

namespace Traverse;

final readonly class TestRunner
{
    public const int MAX_ACTIONS = 10;

    public function __construct(
        private Reasoning $brain,
        private Browser $hands,
        private Vision $eyes,
    ) {}

    public function run(string $test): void
    {
        $history = [];
        $snapshot = null;

        while ($next = $this->brain->decideNext($test, $snapshot, $history)) {
            $snapshot = $this->eyes->describeUI($this->hands->execute($next));
            $history = $this->guardHistory($history, $next);
        }
    }

    /**
     * @param Action[] $history
     *
     * @return Action[]
     */
    private function guardHistory(array $history, Action $next): array
    {
        if (count($history) > self::MAX_ACTIONS) {
            throw new TooManyActionsException();
        }

        $history[] = $next;

        return $history;
    }
}
