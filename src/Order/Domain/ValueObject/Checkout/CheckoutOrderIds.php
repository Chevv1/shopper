<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Checkout;

use App\Order\Domain\ValueObject\Order\OrderId;
use App\Shared\Domain\ValueObject\CollectionValue;

final readonly class CheckoutOrderIds extends CollectionValue
{
    protected static function itemType(): string
    {
        return OrderId::class;
    }
}
