<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\EventSubscriber;

use App\Order\Domain\Event\OrderPlacedEvent;
use App\Shared\Infrastructure\Event\OrderPlacedIntegrationEvent;
use App\Shared\Infrastructure\EventSubscriber\AbstractEventSubscriber;

final readonly class OrderPlacedTranslator extends AbstractEventSubscriber
{
    public function __invoke(OrderPlacedEvent $domainEvent): void
    {
        $integrationEvent = new OrderPlacedIntegrationEvent(
            orderId: $domainEvent->orderId->value(),
            customerId: $domainEvent->customerId->value(),
        );

        $this->eventBus->dispatch($integrationEvent);
    }
}
