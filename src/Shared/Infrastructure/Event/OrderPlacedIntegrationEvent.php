<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure\Event;

final readonly class OrderPlacedIntegrationEvent
{
    public function __construct(
        public string $orderId,
        public string $customerId,
    ) {}
}
