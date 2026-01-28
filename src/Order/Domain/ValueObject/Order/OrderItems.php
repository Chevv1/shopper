<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Order;

use App\Order\Domain\Exception\OrderItemNotFoundException;
use App\Shared\Domain\ValueObject\CollectionValue;

final readonly class OrderItems extends CollectionValue
{
    protected static function itemType(): string
    {
        return OrderItem::class;
    }

    public function calculateTotal(): int
    {
        return array_reduce(
            array: $this->items,
            callback: static fn(int $sum, OrderItem $item): int => $item->subtotal()->add($sum)->value(),
            initial: 0,
        );
    }

    public function hasProduct(OrderItemProductId $productId): bool
    {
        return array_any(
            array: $this->items,
            callback: static fn(OrderItem $item): bool => $item->productId()->equals($productId),
        );
    }

    public function getByProductId(OrderItemProductId $productId): ?OrderItem
    {
        return array_find(
            array: $this->items,
            callback: static fn(OrderItem $item): bool => $item->productId()->equals($productId),
        ) ?? throw new OrderItemNotFoundException;
    }
}
