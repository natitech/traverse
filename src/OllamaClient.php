<?php

namespace Traverse;

use Symfony\Contracts\HttpClient\HttpClientInterface;

final readonly class OllamaClient
{
    public function __construct(
        private HttpClientInterface $http,
        private string $baseUrl,
        private string $model,
        private string $systemPrompt,
        private array $options = [],
    ) {}

    public function chat(string $userPrompt): string
    {
        $response = $this->http->request('POST', $this->baseUrl, [
            'json' => [
                'model' => $this->model,
                'messages' => [
                    ['role' => 'system', 'content' => $this->systemPrompt],
                    ['role' => 'user', 'content' => $userPrompt],
                ],
                'stream' => false,
                'options' => $this->options,
            ],
        ]);

        $data = $response->toArray();

        return $data['message']['content'] ?? '';
    }
}
