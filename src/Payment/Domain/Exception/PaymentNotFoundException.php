<?php

declare(strict_types=1);

namespace App\Payment\Domain\Exception;

use App\Payment\Domain\Entity\PaymentId;
use App\Shared\Domain\Exception\DomainException;

final class PaymentNotFoundException extends DomainException
{
    public static function byId(PaymentId $id): self
    {
        return new self(message: "Payment #`{$id}` was not found.");
    }
}
