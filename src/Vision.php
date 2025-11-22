<?php

namespace Traverse;

interface Vision
{
    /** Convert the HTML into a text-based UI map with XPATH expressions for actionable elements */
    public function describeUI(string $html): string;
}
