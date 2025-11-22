<?php

namespace Traverse;

use Psr\Log\LoggerInterface;

final readonly class OllamaReasoning implements Reasoning
{
    public function __construct(
        private OllamaClient $client,
        private LoggerInterface $logger,
    ) {}

    public function decideNext(string $goal, ?string $snapshot, array $history): ?Action
    {
        return $this->parseResponse(
            $this->client->chat(
                'GOAL: ' . $goal . "\n"
                . "\n"
                . 'CURRENT PAGE STATE: ' . "\n"
                . $snapshot . "\n"
                . "\n"
                . 'ACTION HISTORY (Do not repeat mistakes):'
                . implode("\n - ", array_map(fn(Action $action) => $action->description, $history))
                . "\n"
                . 'What is the next step? JSON Only.',
            ),
        );
    }

    private function parseResponse(string $jsonContent): ?Action
    {
        try {
            $data = json_decode($jsonContent, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new NoActionFoundException('Invalid JSON');
        }

        return $this->guardAction($data);
    }

    private function guardAction(array $data): ?Action
    {
        $actionName = $data['name'] ?? 'fail';
        $parameters = $data['parameters'] ?? [];
        $description = $data['description'] ?? null;

        if ($actionName === 'fail') {
            throw new NoActionFoundException($parameters['reason'] ?? 'No reason');
        }

        return $actionName === 'succeed'
            ? null
            : new Action($this->command($actionName), $parameters, $description);
    }

    private function command(string $actionName): ActionCommand
    {
        return match ($actionName) {
            'navigate' => ActionCommand::NAVIGATE,
            'click' => ActionCommand::CLICK,
            'fill' => ActionCommand::FILL,
            default => throw new NoActionFoundException('Unknown action: ' . $actionName),
        };
    }
}
