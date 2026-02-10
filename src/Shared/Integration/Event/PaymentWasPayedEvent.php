<?php

declare(strict_types=1);

namespace App\Shared\Integration\Event;

final readonly class PaymentWasPayedEvent
{
    public function __construct(
        public string $orderId,
    ) {}
}
