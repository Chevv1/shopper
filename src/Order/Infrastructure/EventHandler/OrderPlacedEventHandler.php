<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\EventHandler;

use App\Order\Domain\Event\OrderPlacedEvent;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Integration\Event\OrderWasPlacedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class OrderPlacedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {}

    public function __invoke(OrderPlacedEvent $event): void
    {
        $this->eventBus->dispatch(
            new OrderWasPlacedEvent(
                orderId: $event->orderId->value(),
                customerId: $event->customerId->value(),
            )
        );
    }
}
