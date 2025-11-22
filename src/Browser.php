<?php

namespace Traverse;

interface Browser
{
    /** Execute the Action and returns the resulting HTML */
    public function execute(Action $action): string;
}
