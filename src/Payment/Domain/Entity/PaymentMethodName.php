<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class PaymentMethodName extends StringValue
{
    protected function validate(): void
    {
    }
}
