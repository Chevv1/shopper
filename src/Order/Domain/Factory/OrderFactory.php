<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderCreatedAt;
use App\Order\Domain\Entity\OrderCustomerId;
use App\Order\Domain\Entity\OrderId;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Domain\Entity\OrderItems;
use App\Order\Domain\Entity\OrderStatus;
use App\Order\Domain\Entity\OrderTotalPrice;
use App\Order\Domain\Entity\OrderUpdatedAt;
use App\Order\Domain\Exception\CannotPlaceOrderException;

final readonly class OrderFactory
{
    public static function create(
        OrderCustomerId $customer,
        OrderItems      $items,
    ): Order {
        if ($items->isEmpty() === true) {
            throw CannotPlaceOrderException::emptyOrder();
        }

        $totalPrice = 0;

        /** @var OrderItem $item */
        foreach ($items as $item) {
            $totalPrice += $item->subtotal()->value();
        }

        return new Order(
            id: OrderId::generate(),
            customerId: $customer,
            status: OrderStatus::pending(),
            items: $items,
            totalPrice: new OrderTotalPrice($totalPrice),
            createdAt: OrderCreatedAt::now(),
            updatedAt: OrderUpdatedAt::now(),
        );
    }
}
