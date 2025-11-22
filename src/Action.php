<?php

namespace Traverse;

final readonly class Action
{
    /** @param array<string, mixed> $parameters */
    public function __construct(
        public ActionCommand $command,
        public array $parameters,
        public string $description,
    ) {}
}
