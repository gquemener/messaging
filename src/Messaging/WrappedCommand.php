<?php

declare (strict_types = 1);

namespace GildasQ\Messaging;

use Symfony\Component\EventDispatcher\Event;

final class WrappedCommand extends Event
{
    private $command;

    public function __construct(Command $command)
    {
        $this->command = $command;
    }

    public function getCommand(): Command
    {
        return $this->command;
    }
}
