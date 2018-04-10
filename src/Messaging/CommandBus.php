<?php

declare (strict_types = 1);

namespace GildasQ\Messaging;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Event;

final class CommandBus
{
    private $eventDispatcher;

    public function __construct(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
    }

    public function dispatch(Command $command): void
    {
        $commandName = $command->getName();
        if (!$this->eventDispatcher->hasListeners($commandName)) {
            throw new \RuntimeException(sprintf(
                'No command handler configured for command "%s".',
                $commandName
            ));
        }

        $event = new class($command) extends Event {
            private $command;

            public function __construct(Command $command)
            {
                $this->command = $command;
            }

            public function getCommand(): Command
            {
                return $this->command;
            }
        };
        $this->eventDispatcher->dispatch($commandName, $event);
    }

    public function addCommandHandler($commandName, $commandHandler)
    {
        if ($this->eventDispatcher->hasListeners($commandName)) {
            throw new \RuntimeException('Command handler already setup for this command.');
        }

        $this->eventDispatcher->addListener($commandName, function($event) use ($commandHandler) {
            return $commandHandler($event->getCommand());
        });
    }
}
