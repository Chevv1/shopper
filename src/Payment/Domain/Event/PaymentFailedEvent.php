<?php

declare(strict_types=1);

namespace App\Payment\Domain\Event;

use App\Shared\Domain\Event\DomainEvent;

final readonly class PaymentFailedEvent extends DomainEvent
{
}
