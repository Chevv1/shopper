<?php

declare(strict_types=1);

namespace App\Order\Domain\Factory;

use App\Order\Domain\Entity\Checkout;
use App\Order\Domain\ValueObject\Checkout\CheckoutCreatedAt;
use App\Order\Domain\ValueObject\Checkout\CheckoutId;
use App\Order\Domain\ValueObject\Checkout\CheckoutOrderIds;
use App\Order\Domain\ValueObject\Checkout\CheckoutStatus;
use App\Order\Domain\ValueObject\Checkout\CheckoutTotalAmount;

final readonly class CheckoutFactory
{
    public static function create(
        CheckoutOrderIds $orderIds,
        CheckoutTotalAmount $totalAmount,
    ): Checkout {
        return new Checkout(
            id: CheckoutId::generate(),
            orderIds: $orderIds,
            totalAmount: $totalAmount,
            status: CheckoutStatus::pending(),
            paidAt: null,
            createdAt: CheckoutCreatedAt::now(),
        );
    }

    public static function createFromOrders(array $orders): Checkout
    {
        $orderIds = [];
        $totalAmount = 0;

        foreach ($orders as $order) {
            $orderIds[] = $order->id()->value();
            $totalAmount += $order->totalPrice()->value();
        }

        return self::create(
            orderIds: new CheckoutOrderIds($orderIds),
            totalAmount: new CheckoutTotalAmount($totalAmount),
        );
    }
}
