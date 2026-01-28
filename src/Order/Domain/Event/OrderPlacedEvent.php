<?php

declare(strict_types=1);

namespace App\Order\Domain\Event;

use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderId;
use App\Shared\Domain\Event\DomainEvent;

final readonly class OrderPlacedEvent extends DomainEvent
{
    public function __construct(
        public OrderId         $orderId,
        public OrderCustomerId $customerId,
    ) {
        parent::__construct();
    }
}
