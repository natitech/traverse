<?php

namespace Traverse\Test;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Traverse\Action;
use Traverse\ActionCommand;
use Traverse\NoActionFoundException;
use Traverse\Test\Double\BrowserMock;
use Traverse\Test\Double\ReasoningMock;
use Traverse\Test\Double\VisionMock;
use Traverse\TestRunner;
use Traverse\TooManyActionsException;

final class TestRunnerTest extends TestCase
{
    private readonly ReasoningMock $brain;

    private readonly TestRunner $runner;

    private readonly BrowserMock $hands;

    private readonly VisionMock $eyes;

    protected function setUp(): void
    {
        parent::setUp();

        $this->brain = new ReasoningMock();
        $this->hands = new BrowserMock();
        $this->eyes = new VisionMock();

        $this->runner = new TestRunner($this->brain, $this->hands, $this->eyes);
    }

    #[Test]
    public function whenNoActionFoundThenThrow(): void
    {
        $this->brain->setNoActionFound(true);

        $this->expectException(NoActionFoundException::class);

        $this->runner->run('This is a description with no possible action.');
    }

    #[Test]
    public function whenTestSucceedAtFirstTryThenNothing(): void
    {
        $this->runner->run('Please succeed immediately');

        $this->assertNoException();
    }

    #[Test]
    public function canExecuteNextActionAndAnalyseUI(): void
    {
        $this->brain->addNextAction(new Action(ActionCommand::NAVIGATE, ['url' => 'http://localhost'], 'Navigate to localhost'));

        $this->runner->run('Go to http://localhost, then go to dashboard, then ensure no errors in displayed.');

        $this->assertEquals('Navigate to localhost', $this->hands->lastActionExecuted()?->description);
        $this->assertEquals('<body><a>Dashboard</a></body>', $this->eyes->lastUIDescribed());
    }

    #[Test]
    public function whenTooManyActionsThenThrow(): void
    {
        for ($i = 0; $i < 15; $i++) {
            $this->brain->addNextAction(new Action(ActionCommand::NAVIGATE, ['url' => 'http://localhost'], 'Navigate to localhost'));
        }

        $this->expectException(TooManyActionsException::class);

        $this->runner->run('Go to http://localhost, then go to dashboard, then ensure no errors in displayed.');
    }

    private function assertNoException(): void
    {
        /** @phpstan-ignore method.alreadyNarrowedType */
        $this->assertTrue(true);
    }
}
