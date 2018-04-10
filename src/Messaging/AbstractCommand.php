<?php

declare (strict_types = 1);

namespace GildasQ\Messaging;

abstract class AbstractCommand implements Command
{
    public function getName(): string
    {
        return get_class($this);
    }
}
