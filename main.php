<?php

require_once __DIR__ . '/vendor/autoload.php';

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\HttpClient\CurlHttpClient;
use Symfony\Component\Panther\Client;
use Traverse\OllamaClient;
use Traverse\OllamaReasoning;
use Traverse\OllamaVision;
use Traverse\PantherBrowser;
use Traverse\TestRunner;

$http = new CurlHttpClient();
$logger = new Logger('traverse', [new StreamHandler('php://stdout')]);

$runner = new TestRunner(
    new OllamaReasoning(
        new OllamaClient(
            $http,
            'http://localhost:11434',
            'llama3',
            file_get_contents(__DIR__ . '/reasonning_system_prompt.md'),
            [],
        ),
        $logger,
    ),
    new PantherBrowser(Client::createChromeClient(), $logger),
    new OllamaVision(
        new OllamaClient(
            $http,
            'http://localhost:11434',
            'deepseek-7B-instruct',
            file_get_contents(__DIR__ . '/vision_system_prompt.md'),
            ['temperature' => 0.1],
        ),
        $logger,
    ),
);

$runner->run('Go to https://www.google.com, search for "php" and ensure it is not empty');
