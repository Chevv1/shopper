<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventHandler;

use App\Shared\Application\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\MessageBusInterface;

abstract readonly class AbstractEventHandler implements EventHandlerInterface
{
    public function __construct(
        #[Target('messenger.bus.event')]
        protected MessageBusInterface $eventBus,
    ) {}
}
