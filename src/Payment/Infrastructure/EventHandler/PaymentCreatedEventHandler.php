<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\EventHandler;

use App\Payment\Domain\Event\PaymentCreatedEvent;
use App\Shared\Application\EventHandlerInterface;

final readonly class PaymentCreatedEventHandler implements EventHandlerInterface
{
    public function __invoke(PaymentCreatedEvent $event): void
    {
    }
}
