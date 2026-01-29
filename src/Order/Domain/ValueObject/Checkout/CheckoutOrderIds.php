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

    public function has(OrderId $orderId): bool
    {
        return array_any(
            array: $this->items,
            callback: static fn(OrderId $item): bool => $item->equals($orderId) === true,
        );
    }

    public function remove(OrderId $orderId): CheckoutOrderIds
    {
        return $this->filter(static fn(OrderId $item): bool => $item->equals($orderId) === true);
    }
}
