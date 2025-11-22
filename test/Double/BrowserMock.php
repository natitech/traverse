<?php

namespace Traverse\Test\Double;

use Traverse\Action;
use Traverse\Browser;

final class BrowserMock implements Browser
{
    private ?Action $lastAction = null;

    public function execute(Action $action): string
    {
        $this->lastAction = $action;

        return '<body><a>Dashboard</a></body>';
    }

    public function lastActionExecuted(): ?Action
    {
        return $this->lastAction;
    }
}
