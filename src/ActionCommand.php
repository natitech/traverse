<?php

namespace Traverse;

enum ActionCommand: string
{
    case NAVIGATE = 'navigate';
    case CLICK = 'click';
    case FILL = 'fill';
}
