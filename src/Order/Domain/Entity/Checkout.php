<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\Event\CheckoutPaidEvent;
use App\Order\Domain\ValueObject\Checkout\CheckoutCreatedAt;
use App\Order\Domain\ValueObject\Checkout\CheckoutId;
use App\Order\Domain\ValueObject\Checkout\CheckoutOrderIds;
use App\Order\Domain\ValueObject\Checkout\CheckoutPaidAt;
use App\Order\Domain\ValueObject\Checkout\CheckoutStatus;
use App\Order\Domain\ValueObject\Checkout\CheckoutTotalAmount;
use App\Shared\Domain\Entity\AggregateRoot;

final class Checkout extends AggregateRoot
{
    public function __construct(
        private readonly CheckoutId          $id,
        private readonly CheckoutOrderIds    $orderIds,
        private readonly CheckoutTotalAmount $totalAmount,
        private CheckoutStatus               $status,
        private ?CheckoutPaidAt              $paidAt,
        private readonly CheckoutCreatedAt   $createdAt,
    ) {}

    // Commands

    public function markAsPaid(): void
    {
        if ($this->status()->isPaid() === true) {
            return;
        }

        $this->status = CheckoutStatus::paid();
        $this->paidAt = CheckoutPaidAt::now();

        $this->recordEvent(new CheckoutPaidEvent(
            checkoutId: $this->id,
            orderIds: $this->orderIds,
        ));
    }

    // Getters

    public function id(): CheckoutId
    {
        return $this->id;
    }

    public function orderIds(): CheckoutOrderIds
    {
        return $this->orderIds;
    }

    public function totalAmount(): CheckoutTotalAmount
    {
        return $this->totalAmount;
    }

    public function status(): CheckoutStatus
    {
        return $this->status;
    }

    public function paidAt(): ?CheckoutPaidAt
    {
        return $this->paidAt;
    }

    public function createdAt(): CheckoutCreatedAt
    {
        return $this->createdAt;
    }
}
