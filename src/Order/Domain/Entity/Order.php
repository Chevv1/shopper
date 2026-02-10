<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\Event\OrderPlacedEvent;
use App\Shared\Domain\Entity\AggregateRoot;

final class Order extends AggregateRoot
{
    public function __construct(
        private readonly OrderId         $id,
        private readonly OrderCustomerId $customerId,
        private OrderStatus              $status,
        private readonly OrderItems      $items,
        private readonly OrderTotalPrice $totalPrice,
        private readonly OrderCreatedAt  $createdAt,
        private OrderUpdatedAt           $updatedAt,
    ) {
        $this->recordEvent(new OrderPlacedEvent(
            orderId: $this->id(),
            customerId: $this->customerId(),
        ));
    }

    // Methods

    public function markAsPaid(): void
    {
        $this->status = OrderStatus::paid();
        $this->updatedAt = OrderUpdatedAt::now();
    }

    // Getters

    public function id(): OrderId
    {
        return $this->id;
    }

    public function customerId(): OrderCustomerId
    {
        return $this->customerId;
    }

    public function status(): OrderStatus
    {
        return $this->status;
    }

    public function items(): OrderItems
    {
        return $this->items;
    }

    public function totalPrice(): OrderTotalPrice
    {
        return $this->totalPrice;
    }

    public function createdAt(): OrderCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): OrderUpdatedAt
    {
        return $this->updatedAt;
    }
}
