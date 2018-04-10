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

        $this->eventDispatcher->dispatch($commandName, new WrappedCommand($command));
    }

    public function addCommandHandler($commandName, $commandHandler)
    {
        if ($this->eventDispatcher->hasListeners($commandName)) {
            throw new \RuntimeException('Command handler already setup for this command.');
        }

        $this->eventDispatcher->addListener($commandName, function(WrappedCommand $wrapped) use ($commandHandler) {
            return $commandHandler($wrapped->getCommand());
        });
    }
}
