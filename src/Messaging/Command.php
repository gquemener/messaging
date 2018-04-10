<?php

declare (strict_types = 1);

namespace GildasQ\Messaging;

use Symfony\Component\EventDispatcher\Event;

interface Command
{
    public function getName(): string;
}
