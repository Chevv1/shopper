<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Exception\CannotPlaceOrderException;
use App\Order\Domain\ValueObject\Order\OrderCreatedAt;
use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderId;
use App\Order\Domain\ValueObject\Order\OrderItems;
use App\Order\Domain\ValueObject\Order\OrderStatus;
use App\Order\Domain\ValueObject\Order\OrderTotalPrice;
use App\Order\Domain\ValueObject\Order\OrderUpdatedAt;

final readonly class OrderFactory
{
    public static function create(
        OrderCustomerId $customer,
        OrderItems      $items,
        OrderTotalPrice $totalPrice,
    ): Order {
        if ($items->isEmpty() === true) {
            throw CannotPlaceOrderException::emptyOrder();
        }

        return new Order(
            id: OrderId::generate(),
            customerId: $customer,
            status: OrderStatus::pending(),
            items: $items,
            totalPrice: $totalPrice,
            createdAt: OrderCreatedAt::now(),
            updatedAt: OrderUpdatedAt::now(),
        );
    }
}
