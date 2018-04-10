<?php

require 'vendor/autoload.php';

use GildasQ\Messaging\AbstractCommand;
use GildasQ\Messaging\CommandBus;
use Symfony\Component\EventDispatcher\EventDispatcher;

$sayHello = new class('World') extends AbstractCommand {
    private $to;

    public function __construct(string $to)
    {
        $this->to = $to;
    }

    public function getName(): string
    {
        return 'sayHello';
    }

    public function to(): string
    {
        return $this->to;
    }
};

$eventDispatcher = new EventDispatcher();
$commandBus = new CommandBus($eventDispatcher);

$commandBus->addCommandHandler('sayHello', function($command) {
    printf('Hello %s', $command->to());
});

try {
    $commandBus->dispatch($sayHello);
} catch (\Exception $e) {
    echo $e->getMessage();
}
