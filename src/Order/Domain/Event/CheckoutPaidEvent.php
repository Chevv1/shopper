<?php

declare(strict_types=1);

namespace App\Order\Domain\Event;

use App\Order\Domain\ValueObject\Checkout\CheckoutId;
use App\Order\Domain\ValueObject\Checkout\CheckoutOrderIds;
use App\Shared\Domain\Event\DomainEvent;

final readonly class CheckoutPaidEvent extends DomainEvent
{
    public function __construct(
        public CheckoutId       $checkoutId,
        public CheckoutOrderIds $orderIds,
    ) {
        parent::__construct();
    }
}
