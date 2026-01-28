<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\ValueObject\Order\OrderItem;
use App\Order\Domain\ValueObject\Order\OrderItemPrice;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderItemQuantity;

final readonly class OrderItemFactory
{
    public static function create(
        OrderItemProductId $productId,
        OrderItemQuantity  $quantity,
        OrderItemPrice     $price
    ): OrderItem {
        return new OrderItem(
            productId: $productId,
            quantity: $quantity,
            price: $price
        );
    }
}
