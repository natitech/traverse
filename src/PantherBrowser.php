<?php

namespace Traverse;

use Facebook\WebDriver\WebDriverBy;
use Facebook\WebDriver\WebDriverElement;
use Facebook\WebDriver\WebDriverSelect;
use Psr\Log\LoggerInterface;
use Symfony\Component\Panther\Client;

final readonly class PantherBrowser implements Browser
{
    public function __construct(private Client $panther, private LoggerInterface $logger) {}

    public function execute(Action $action): string
    {
        $this->logger->info('Executing action', ['description' => $action->description, 'action' => $action->command->value, 'parameters' => $action->parameters]);

        if ($action->command === ActionCommand::NAVIGATE) {
            $this->panther->get($action->parameters['url']);
        } elseif ($action->command === ActionCommand::CLICK) {
            $this->getElement($action)->click();
        } elseif ($action->command === ActionCommand::FILL) {
            $element = $this->getElement($action);

            if ($element->getTagName() === 'select') {
                (new WebDriverSelect($element))->selectByValue($action->parameters['value']);
            } else {
                $element->clear();
                $element->sendKeys($action->parameters['value']);
            }
        }

        return $this->panther->getPageSource();
    }

    private function getElement(Action $action): WebDriverElement
    {
        if ($action->parameters['selector'] === "//input[@name='q']") {
            return $this->panther->findElement(WebDriverBy::xpath("//textarea[@name='q']"));
        }

        return $this->panther->findElement(WebDriverBy::xpath($action->parameters['selector']));
    }
}
