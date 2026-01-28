<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\Event\OrderPlacedEvent;
use App\Order\Domain\ValueObject\Order\OrderCreatedAt;
use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderId;
use App\Order\Domain\ValueObject\Order\OrderItems;
use App\Order\Domain\ValueObject\Order\OrderStatus;
use App\Order\Domain\ValueObject\Order\OrderTotalPrice;
use App\Order\Domain\ValueObject\Order\OrderUpdatedAt;
use App\Shared\Domain\Entity\AggregateRoot;

final class Order extends AggregateRoot
{
    public function __construct(
        private readonly OrderId         $id,
        private readonly OrderCustomerId $customerId,
        private readonly OrderStatus     $status,
        private readonly OrderItems      $items,
        private readonly OrderTotalPrice $totalPrice,
        private readonly OrderCreatedAt  $createdAt,
        private readonly OrderUpdatedAt  $updatedAt,
    ) {
        $this->recordEvent(new OrderPlacedEvent(
            orderId: $this->id(),
            customerId: $this->customerId(),
        ));
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
