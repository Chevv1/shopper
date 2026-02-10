<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\Entity\OrderItemId;
use App\Order\Domain\Entity\OrderItemPrice;
use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderItemQuantity;

final readonly class OrderItemFactory
{
    public static function create(
        OrderItemProductId $productId,
        OrderItemQuantity  $quantity,
        OrderItemPrice     $price
    ): OrderItem {
        return new OrderItem(
            id: OrderItemId::generate(),
            productId: $productId,
            quantity: $quantity,
            price: $price
        );
    }
}
