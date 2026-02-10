<?php

declare(strict_types=1);

namespace App\Payment\Domain\Exception;

use App\Payment\Domain\Entity\PaymentOrderId;
use App\Shared\Domain\Exception\DomainException;

final class UnableToCreatePaymentException extends DomainException
{
    public static function orderNotFound(PaymentOrderId $orderId): self
    {
        return new self(message: "Order #{$orderId->value()} not found");
    }
}
