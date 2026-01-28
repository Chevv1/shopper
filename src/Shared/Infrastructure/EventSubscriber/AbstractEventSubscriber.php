<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\EventSubscriber;

use App\Shared\Domain\Event\EventHandlerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\Messenger\MessageBusInterface;

abstract readonly class AbstractEventSubscriber implements EventHandlerInterface
{
    public function __construct(
        #[Target('messenger.bus.event')]
        protected MessageBusInterface $eventBus,
    ) {}
}
