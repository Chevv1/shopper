<?php

declare(strict_types=1);

namespace App\Payment\Domain\Event;

use App\Payment\Domain\Entity\PaymentOrderId;
use App\Shared\Domain\Event\DomainEvent;

final readonly class PaymentPayedEvent extends DomainEvent
{
    public function __construct(
        public PaymentOrderId $orderId,
    ) {
        parent::__construct();
    }
}
