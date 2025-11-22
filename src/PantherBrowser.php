<?php

namespace Traverse;

use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;

final readonly class PantherBrowser implements Browser
{
    public function __construct(private Client $panther, private LoggerInterface $logger) {}

    public function execute(Action $action): string
    {
        if ($action->command === ActionCommand::NAVIGATE) {
            $this->panther->get($action->parameters['url']);
        } elseif ($action->command === ActionCommand::CLICK) {
            $this->panther->clickLink($action->parameters['selector']);
        } elseif ($action->command === ActionCommand::FILL) {
            //TODO
        }

        return $this->panther->getPageSource();
    }
}
