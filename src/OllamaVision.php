<?php

namespace Traverse;

use Psr\Log\LoggerInterface;

final readonly class OllamaVision implements Vision
{
    public function __construct(
        private OllamaClient $client,
        private LoggerInterface $logger,
    ) {}

    public function describeUI(string $html): string
    {
        $cleanHtml = preg_replace(
            [
                '/<script\b[^>]*>(.*?)<\/script>/is',
                '/<style\b[^>]*>(.*?)<\/style>/is',
                '/<svg\b[^>]*>(.*?)<\/svg>/is',
                '/<link(.*?)\/>/is',
                '/<!--(.*?)-->/s',
            ],
            '',
            $html,
        );

        $cleanHtml = preg_replace('/\s+/', ' ', $cleanHtml);

        //$cleanHtml = substr($cleanHtml, 0, 30000);

        $this->logger->info('Trying to describe UI and elements after action');

        return $this->client->chat(
            'Convert this HTML: ' . "\n"
            . '```html' . "\n"
            . $cleanHtml . "\n"
            . '```',
        );
    }
}
