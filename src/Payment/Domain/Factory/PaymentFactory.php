<?php

declare(strict_types=1);

namespace App\Payment\Domain\Factory;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentAmount;
use App\Payment\Domain\Entity\PaymentCreatedAt;
use App\Payment\Domain\Entity\PaymentId;
use App\Payment\Domain\Entity\PaymentMethodId;
use App\Payment\Domain\Entity\PaymentOrderId;
use App\Payment\Domain\Entity\PaymentOwnerId;
use App\Payment\Domain\Entity\PaymentStatus;
use App\Payment\Domain\Entity\PaymentUpdatedAt;

final readonly class PaymentFactory
{
    public static function create(
        PaymentOrderId  $orderId,
        PaymentOwnerId  $ownerId,
        PaymentMethodId $methodId,
        PaymentAmount   $amount,
    ): Payment {
        return new Payment(
            id: PaymentId::generate(),
            orderId: $orderId,
            ownerId: $ownerId,
            methodId: $methodId,
            status: PaymentStatus::pending(),
            amount: $amount,
            paymentUrl: null,
            createdAt: PaymentCreatedAt::now(),
            updatedAt: PaymentUpdatedAt::now()
        );
    }
}
