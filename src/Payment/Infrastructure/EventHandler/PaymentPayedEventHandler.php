<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\EventHandler;

use App\Payment\Domain\Event\PaymentPayedEvent;
use App\Shared\Application\EventHandlerInterface;
use App\Shared\Integration\Event\PaymentWasPayedEvent;
use Symfony\Component\Messenger\MessageBusInterface;

final readonly class PaymentPayedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private MessageBusInterface $eventBus,
    ) {}

    public function __invoke(PaymentPayedEvent $event): void
    {
        $this->eventBus->dispatch(
            new PaymentWasPayedEvent(
                orderId: $event->orderId->value(),
            )
        );
    }
}
