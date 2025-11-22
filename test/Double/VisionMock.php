<?php

namespace Traverse\Test\Double;

use Traverse\Vision;

final class VisionMock implements Vision
{
    private ?string $lastUIDescribed = null;

    public function describeUI(string $html): string
    {
        $this->lastUIDescribed = $html;

        return 'A page with a dashboard link.';
    }

    public function lastUIDescribed(): string
    {
        return $this->lastUIDescribed;
    }
}
