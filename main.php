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
            'llama3.1:8b',
            file_get_contents(__DIR__ . '/reasonning_system_prompt.md'),
        ),
        $logger,
    ),
    new PantherBrowser(Client::createFirefoxClient(), $logger),
    new OllamaVision(
        new OllamaClient(
            $http,
            'http://localhost:11434',
            'deepseek-coder:6.7b',
            file_get_contents(__DIR__ . '/vision_system_prompt.md'),
        ),
        $logger,
    ),
);

$runner->run('Go to https://www.google.com, fill the search query with "php", do not click on a result link, click on search button, and ensure you can see at least one result in the next page.');
