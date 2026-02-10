<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Payment\Domain\Event\PaymentCreatedEvent;
use App\Payment\Domain\Event\PaymentPayedEvent;
use App\Payment\Domain\Event\PaymentFailedEvent;
use App\Shared\Domain\Entity\AggregateRoot;
use App\Payment\Domain\Exception\PaymentAlreadyProcessedException;

final class Payment extends AggregateRoot
{
    public function __construct(
        private readonly PaymentId        $id,
        private readonly PaymentOrderId   $orderId,
        private readonly PaymentOwnerId   $ownerId,
        private readonly PaymentMethodId  $methodId,
        private PaymentStatus             $status,
        private readonly PaymentAmount    $amount,
        private ?PaymentUrl               $paymentUrl,
        private readonly PaymentCreatedAt $createdAt,
        private PaymentUpdatedAt          $updatedAt,
    ) {
        $this->recordEvent(new PaymentCreatedEvent());
    }

    // Methods

    public function setPaymentUrl(PaymentUrl $paymentUrl): void
    {
        $this->paymentUrl = $paymentUrl;
        $this->updatedAt = PaymentUpdatedAt::now();
    }

    public function markAsPaid(): void
    {
        if ($this->status->isPending() === false) {
            throw new PaymentAlreadyProcessedException();
        }

        $this->status = PaymentStatus::success();
        $this->updatedAt = PaymentUpdatedAt::now();

        $this->recordEvent(new PaymentPayedEvent(
            orderId: $this->orderId,
        ));
    }

    public function markAsFailed(): void
    {
        if ($this->status->isPending() === false) {
            throw new PaymentAlreadyProcessedException();
        }

        $this->status = PaymentStatus::failed();
        $this->updatedAt = PaymentUpdatedAt::now();

        $this->recordEvent(new PaymentFailedEvent());
    }

    // Getters

    public function id(): PaymentId
    {
        return $this->id;
    }

    public function orderId(): PaymentOrderId
    {
        return $this->orderId;
    }

    public function ownerId(): PaymentOwnerId
    {
        return $this->ownerId;
    }

    public function methodId(): PaymentMethodId
    {
        return $this->methodId;
    }

    public function status(): PaymentStatus
    {
        return $this->status;
    }

    public function amount(): PaymentAmount
    {
        return $this->amount;
    }

    public function paymentUrl(): ?PaymentUrl
    {
        return $this->paymentUrl;
    }

    public function createdAt(): PaymentCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): PaymentUpdatedAt
    {
        return $this->updatedAt;
    }
}
