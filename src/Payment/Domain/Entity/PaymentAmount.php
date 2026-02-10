<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class PaymentAmount extends IntegerValue
{
    protected function validate(): void
    {
    }
}
